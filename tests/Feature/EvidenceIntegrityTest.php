<?php

namespace Tests\Feature;

use App\Exceptions\EvidenceProtectionException;
use App\Filament\Resources\CaseResource;
use App\Models\CaseLog;
use App\Models\CaseModel;
use App\Models\Media;
use App\Models\Person;
use App\Models\Station;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

/**
 * Locks in the evidence-preservation rules: append-only audit log, immutable media,
 * hashed uploads, private authorised file access, and closed-case protection.
 */
class EvidenceIntegrityTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    private User $photographer;

    private User $otherPhotographer;

    private Station $station;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('evidence');

        $this->admin = User::factory()->create(['role' => 'ADMIN']);
        $this->photographer = User::factory()->create(['role' => 'PHOTOGRAPHER']);
        $this->otherPhotographer = User::factory()->create(['role' => 'PHOTOGRAPHER']);
        $this->station = Station::create(['name' => 'Harare Central']);
    }

    private function makeCase(?User $photographer = null): CaseModel
    {
        $this->actingAs($this->admin);

        return CaseModel::create([
            'reference_number' => 'CR 1/26',
            'station_id' => $this->station->id,
            'photographer_id' => ($photographer ?? $this->photographer)->id,
            'case_type' => 'Murder',
            'status' => 'OPEN',
        ]);
    }

    private function putFile(string $name, string $contents = 'evidence-bytes'): string
    {
        $path = 'case-media/'.$name;
        Storage::disk('evidence')->put($path, $contents);

        return $path;
    }

    private function makeMedia(CaseModel $case, string $name = 'photo1.jpg', string $contents = 'jpeg-bytes'): Media
    {
        $path = $this->putFile($name, $contents);

        return Media::create([
            'case_id' => $case->id,
            'uploaded_by' => $this->photographer->id,
            'title' => 'Scene',
            'file_path' => [$path],
            'file_hashes' => [$path => hash('sha256', $contents)],
        ]);
    }

    private function logs(CaseModel $case, string $action)
    {
        return CaseLog::where('case_id', $case->id)->where('action', $action);
    }

    // ---------------------------------------------------------------- case numbering / lifecycle

    public function test_scene_reference_numbers_increment_within_the_year(): void
    {
        $first = $this->makeCase();
        $second = $this->makeCase();
        $year = now()->format('Y');

        $this->assertSame("STUDIOS 01/{$year}", $first->scene_reference_number);
        $this->assertSame("STUDIOS 02/{$year}", $second->scene_reference_number);
        $this->assertSame($this->admin->id, $first->created_by);
    }

    public function test_creating_a_case_writes_an_audit_entry(): void
    {
        $case = $this->makeCase();

        $this->assertSame(1, $this->logs($case, 'CASE_CREATED')->count());
    }

    public function test_a_case_cannot_be_deleted(): void
    {
        $case = $this->makeCase();

        $this->expectException(EvidenceProtectionException::class);
        $case->delete();
    }

    public function test_scene_reference_number_cannot_be_changed(): void
    {
        $case = $this->makeCase();

        $this->expectException(EvidenceProtectionException::class);
        $case->update(['scene_reference_number' => 'STUDIOS 99/2026']);
    }

    // ---------------------------------------------------------------- audit trail

    public function test_audit_entries_cannot_be_edited(): void
    {
        $case = $this->makeCase();
        $log = $this->logs($case, 'CASE_CREATED')->firstOrFail();

        $this->expectException(EvidenceProtectionException::class);
        $log->update(['description' => 'tampered']);
    }

    public function test_audit_entries_cannot_be_deleted(): void
    {
        $case = $this->makeCase();
        $log = $this->logs($case, 'CASE_CREATED')->firstOrFail();

        $this->expectException(EvidenceProtectionException::class);
        $log->delete();
    }

    public function test_field_changes_are_logged_with_before_and_after_values(): void
    {
        $case = $this->makeCase();

        $this->actingAs($this->photographer);
        $case->update(['circumstances' => 'Found at the scene']);

        $log = $this->logs($case, 'CASE_UPDATED')->firstOrFail();

        $this->assertSame(['from' => null, 'to' => 'Found at the scene'], $log->metadata['changes']['circumstances']);
        $this->assertSame($this->photographer->id, $log->user_id);
        $this->assertSame('PHOTOGRAPHER', $log->role);
    }

    public function test_reassignment_is_logged_with_the_new_photographer_and_reason(): void
    {
        $case = $this->makeCase();

        $this->actingAs($this->admin);
        $case->auditReason = 'Original photographer on leave';
        $case->update(['photographer_id' => $this->otherPhotographer->id]);

        $log = $this->logs($case, 'REASSIGNED')->firstOrFail();

        $this->assertStringContainsString($this->otherPhotographer->name, $log->description);
        $this->assertSame('Original photographer on leave', $log->metadata['reason']);
        $this->assertSame($this->otherPhotographer->id, $log->metadata['new_photographer_id']);
    }

    public function test_status_change_is_logged(): void
    {
        $case = $this->makeCase();

        $this->actingAs($this->photographer);
        $case->update(['status' => 'PENDING_REVIEW']);

        $this->assertSame(1, $this->logs($case, 'STATUS_CHANGED')->count());
    }

    // ---------------------------------------------------------------- closed cases

    public function test_photographer_cannot_close_a_case(): void
    {
        $case = $this->makeCase();

        $this->actingAs($this->photographer);
        $this->expectException(EvidenceProtectionException::class);
        $case->update(['status' => 'CLOSED']);
    }

    public function test_a_closed_case_rejects_further_changes(): void
    {
        $case = $this->makeCase();

        $this->actingAs($this->admin);
        $case->update(['status' => 'CLOSED']);

        $this->actingAs($this->photographer);
        $this->expectException(EvidenceProtectionException::class);
        $case->update(['circumstances' => 'edited after closing']);
    }

    public function test_only_an_admin_can_reopen_a_closed_case(): void
    {
        $case = $this->makeCase();

        $this->actingAs($this->admin);
        $case->update(['status' => 'CLOSED']);
        $case->update(['status' => 'OPEN']);

        $this->assertSame('OPEN', $case->fresh()->status);
        $this->assertSame(2, $this->logs($case, 'STATUS_CHANGED')->count());
    }

    public function test_media_cannot_be_added_to_a_closed_case(): void
    {
        $case = $this->makeCase();

        $this->actingAs($this->admin);
        $case->update(['status' => 'CLOSED']);

        $this->expectException(EvidenceProtectionException::class);
        $this->makeMedia($case);
    }

    // ---------------------------------------------------------------- media records

    public function test_media_records_cannot_be_deleted(): void
    {
        $media = $this->makeMedia($this->makeCase());

        $this->expectException(EvidenceProtectionException::class);
        $media->delete();
    }

    public function test_media_records_cannot_be_edited(): void
    {
        $media = $this->makeMedia($this->makeCase());

        $this->expectException(EvidenceProtectionException::class);
        $media->update(['title' => 'renamed']);
    }

    public function test_recorded_hashes_cannot_be_overwritten(): void
    {
        $media = $this->makeMedia($this->makeCase());

        $this->expectException(EvidenceProtectionException::class);
        $media->update(['file_hashes' => ['case-media/photo1.jpg' => str_repeat('a', 64)]]);
    }

    public function test_media_can_only_be_removed_once_and_the_file_is_kept(): void
    {
        $media = $this->makeMedia($this->makeCase());
        $path = $media->getFilePaths()[0];

        $media->markRemoved($this->admin->id, 'Uploaded to the wrong case');

        $this->assertTrue($media->fresh()->isRemoved());
        $this->assertTrue(Storage::disk('evidence')->exists($path));

        $this->expectException(EvidenceProtectionException::class);
        $media->fresh()->markRemoved($this->admin->id, 'again');
    }

    // ---------------------------------------------------------------- uploads (syncCaseMedia)

    public function test_upload_is_hashed_attributed_to_the_session_user_and_logged(): void
    {
        $case = $this->makeCase();
        $path = $this->putFile('scene1.jpg', 'evidence-bytes');

        $this->actingAs($this->photographer);
        CaseResource::syncCaseMedia($case, [[
            'title' => 'Scene',
            'file_path' => [$path],
            'uploaded_by' => $this->admin->id, // a forged value must be ignored
        ]]);

        $media = Media::firstOrFail();

        $this->assertSame(hash('sha256', 'evidence-bytes'), $media->file_hashes[$path]);
        $this->assertSame($this->photographer->id, $media->uploaded_by);
        $this->assertSame(1, $this->logs($case, 'MEDIA_UPLOADED')->count());
        $this->assertSame(
            hash('sha256', 'evidence-bytes'),
            $this->logs($case, 'MEDIA_UPLOADED')->first()->metadata['files'][0]['sha256'],
        );
    }

    public function test_saving_again_never_removes_or_duplicates_existing_media(): void
    {
        $case = $this->makeCase();
        $path = $this->putFile('scene1.jpg');

        $this->actingAs($this->photographer);
        CaseResource::syncCaseMedia($case, [['title' => 'Scene', 'file_path' => [$path]]]);
        CaseResource::syncCaseMedia($case, []);
        CaseResource::syncCaseMedia($case, [['title' => 'Scene', 'file_path' => [$path]]]);

        $this->assertSame(1, Media::count());
        $this->assertSame(1, $this->logs($case, 'MEDIA_UPLOADED')->count());
        $this->assertTrue(Storage::disk('evidence')->exists($path));
    }

    public function test_untrusted_paths_are_ignored(): void
    {
        $case = $this->makeCase();
        $this->putFile('real.jpg');

        $this->actingAs($this->photographer);
        CaseResource::syncCaseMedia($case, [[
            'title' => 'Bad',
            'file_path' => ['../.env', 'case-media/../secret.txt', 'other-folder/x.jpg', 'case-media/does-not-exist.jpg'],
        ]]);

        $this->assertSame(0, Media::count());
    }

    public function test_a_file_already_attached_to_another_case_cannot_be_claimed(): void
    {
        $caseA = $this->makeCase();
        $caseB = $this->makeCase();
        $path = $this->putFile('shared.jpg');

        $this->actingAs($this->photographer);
        CaseResource::syncCaseMedia($caseA, [['title' => 'A', 'file_path' => [$path]]]);
        CaseResource::syncCaseMedia($caseB, [['title' => 'B', 'file_path' => [$path]]]);

        $this->assertSame(1, Media::where('case_id', $caseA->id)->count());
        $this->assertSame(0, Media::where('case_id', $caseB->id)->count());
    }

    public function test_a_photographer_cannot_add_media_to_someone_elses_case(): void
    {
        $case = $this->makeCase();
        $path = $this->putFile('scene1.jpg');

        $this->actingAs($this->otherPhotographer);
        $this->expectException(AuthorizationException::class);
        CaseResource::syncCaseMedia($case, [['title' => 'Nope', 'file_path' => [$path]]]);
    }

    // ---------------------------------------------------------------- people

    public function test_editing_a_person_never_alters_the_record_other_cases_use(): void
    {
        $caseA = $this->makeCase();
        $caseB = $this->makeCase();

        $this->actingAs($this->photographer);

        $person = ['role' => 'witness', 'first_name' => 'Tendai', 'surname' => 'Moyo', 'id_number' => '18-118656Q18'];

        CaseResource::syncCasePeople($caseA, [$person]);
        CaseResource::syncCasePeople($caseB, [$person]);
        $this->assertSame(1, Person::count(), 'identical details should reuse the same row');

        CaseResource::syncCasePeople($caseB, [$person + ['email' => 'tendai@example.com']]);

        $this->assertSame(2, Person::count());
        $this->assertNull(Person::orderBy('id')->first()->email, "case A's person must be untouched");
        $this->assertSame(1, $this->logs($caseA, 'PEOPLE_UPDATED')->count());
    }

    // ---------------------------------------------------------------- file access route

    private function fileUrl(Media $media): string
    {
        return route('cases.media.file', ['case' => $media->case_id, 'media' => $media->id, 'index' => 0]);
    }

    public function test_guests_cannot_read_evidence_files(): void
    {
        $media = $this->makeMedia($this->makeCase());
        $this->app['auth']->forgetGuards(); // makeCase() acted as an admin; start the request as a guest

        $this->get($this->fileUrl($media))->assertStatus(401);
    }

    public function test_the_assigned_photographer_can_read_the_file_and_it_is_not_cacheable(): void
    {
        $media = $this->makeMedia($this->makeCase());

        $response = $this->actingAs($this->photographer)->get($this->fileUrl($media));

        $response->assertOk();
        $this->assertStringContainsString('no-store', $response->headers->get('Cache-Control'));
        $this->assertStringContainsString('private', $response->headers->get('Cache-Control'));
        $this->assertSame('nosniff', $response->headers->get('X-Content-Type-Options'));
    }

    public function test_an_admin_can_read_the_file(): void
    {
        $media = $this->makeMedia($this->makeCase());

        $this->actingAs($this->admin)->get($this->fileUrl($media))->assertOk();
    }

    public function test_another_photographer_cannot_read_the_file(): void
    {
        $media = $this->makeMedia($this->makeCase());

        $this->actingAs($this->otherPhotographer)->get($this->fileUrl($media))->assertForbidden();
    }

    public function test_a_deactivated_photographer_cannot_read_the_file(): void
    {
        $media = $this->makeMedia($this->makeCase());
        $this->photographer->update(['is_active' => false]);

        $this->actingAs($this->photographer)->get($this->fileUrl($media))->assertForbidden();
    }

    public function test_a_media_id_from_a_different_case_is_not_found(): void
    {
        $caseA = $this->makeCase();
        $caseB = $this->makeCase();
        $media = $this->makeMedia($caseA);

        $url = route('cases.media.file', ['case' => $caseB->id, 'media' => $media->id, 'index' => 0]);

        $this->actingAs($this->admin)->get($url)->assertNotFound();
    }

    public function test_a_removed_item_is_gone(): void
    {
        $media = $this->makeMedia($this->makeCase());
        $media->markRemoved($this->admin->id, 'Wrong case');

        $this->actingAs($this->admin)->get($this->fileUrl($media))->assertStatus(410);
    }

    public function test_a_tampered_stored_path_is_never_served(): void
    {
        $case = $this->makeCase();
        $media = Media::create([
            'case_id' => $case->id,
            'uploaded_by' => $this->photographer->id,
            'title' => 'Tampered',
            'file_path' => ['../.env'],
        ]);

        $this->actingAs($this->admin)->get($this->fileUrl($media))->assertNotFound();
    }
}
