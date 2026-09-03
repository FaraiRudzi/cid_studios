<?php

namespace Tests\Feature;

use App\Filament\Resources\CaseResource;
use App\Models\CaseModel;
use App\Models\Media;
use App\Models\Station;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CasePeopleAndMediaPersistenceTest extends TestCase
{
    use RefreshDatabase;

    public function test_case_people_and_media_are_saved_with_their_role_and_file_data(): void
    {
        $user = User::create([
            'force_number' => 'F12345',
            'first_name' => 'Admin',
            'surname' => 'User',
            'email' => 'admin@example.com',
            'phone_number' => '0710000000',
            'role' => 'ADMIN',
            'password' => 'secret123',
            'is_active' => true,
        ]);

        $station = Station::create([
            'name' => 'Johannesburg Central',
            'code' => 'JC',
            'province' => 'Gauteng',
        ]);

        $case = CaseModel::create([
            'scene_reference_number' => 'SR-CASE-001',
            'reference_number' => 'CR-1234',
            'station_id' => $station->id,
            'photographer_id' => $user->id,
            'case_type' => 'Sudden Death',
            'circumstances' => 'Test case',
            'status' => 'OPEN',
            'created_by' => $user->id,
        ]);

        CaseResource::syncCasePeople($case, [
            [
                'role' => 'informant',
                'first_name' => 'Jane',
                'surname' => 'Doe',
                'id_number' => '9001010001080',
                'gender' => 'FEMALE',
                'phone_number' => '0711111111',
                'address' => '12 Main Road, Harare',
                'notes' => 'Witnessed the incident',
            ],
            [
                'role' => 'accused',
                'first_name' => 'John',
                'surname' => 'Smith',
                'id_number' => '8802020002081',
                'gender' => 'MALE',
                'phone_number' => '0722222222',
                'address' => '32 High Street, Bulawayo',
                'notes' => 'Suspect statement',
            ],
        ]);

        CaseResource::syncCaseMedia($case, [
            [
                'title' => 'Scene photos',
                'file_path' => ['case-media/scene-1.jpg'],
                'file_type' => 'image/jpeg',
                'file_size' => 1234,
                'uploaded_by' => $user->id,
            ],
            [
                'title' => 'Scene video',
                'file_path' => ['case-media/scene-2.mp4'],
                'file_type' => 'video/mp4',
                'file_size' => 4567,
                'uploaded_by' => $user->id,
            ],
        ]);

        $this->assertDatabaseHas('case_person', ['case_id' => $case->id, 'role' => 'informant']);
        $this->assertDatabaseHas('case_person', ['case_id' => $case->id, 'role' => 'accused']);
        $this->assertDatabaseCount('case_person', 2);

        $this->assertSame('12 Main Road, Harare', $case->people()->where('first_name', 'Jane')->first()->address);
        $this->assertSame('32 High Street, Bulawayo', $case->people()->where('first_name', 'John')->first()->address);

        $this->assertDatabaseHas('media', ['case_id' => $case->id, 'title' => 'Scene photos']);
        $this->assertDatabaseHas('media', ['case_id' => $case->id, 'title' => 'Scene video']);
        $this->assertDatabaseCount('media', 2);
        $this->assertDatabaseHas('case_logs', [
            'case_id' => $case->id,
            'action' => 'MEDIA_UPLOADED',
            'description' => 'Media uploaded by photographer: Admin User.',
        ]);
        $this->assertSame(
            ['case-media/scene-1.jpg'],
            Media::query()->where('case_id', $case->id)->where('title', 'Scene photos')->first()->getFilePaths(),
        );
    }
}
