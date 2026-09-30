<?php

namespace App\Observers;

use App\Exceptions\EvidenceProtectionException;
use App\Models\CaseLog;
use App\Models\CaseModel;
use App\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use RuntimeException;

class CaseObserver
{
    /** Case fields whose creation values and changes are audited. */
    private const TRACKED = [
        'reference_number', 'station_id', 'photographer_id', 'case_type',
        'circumstances', 'cause_of_death', 'status',
    ];

    public function creating(CaseModel $case): void
    {
        $actorId = Auth::id() ?? $case->created_by;

        if (! $actorId) {
            throw new RuntimeException('A case cannot be created without an authenticated user.');
        }

        $case->created_by = $actorId;
        $case->scene_reference_number = self::nextSceneReference();
    }

    public function created(CaseModel $case): void
    {
        $this->log($case, 'CASE_CREATED',
            "Case {$case->scene_reference_number} created and assigned to photographer (ID: {$case->photographer_id}).",
            ['snapshot' => Arr::only($case->getAttributes(), self::TRACKED)]
        );
    }

    public function updating(CaseModel $case): void
    {
        foreach (['scene_reference_number', 'created_by'] as $field) {
            if ($case->isDirty($field)) {
                throw new EvidenceProtectionException("{$field} cannot be changed once a case exists.");
            }
        }

        $isAdmin = Auth::user()?->role === 'ADMIN';

        if ($case->isDirty('status')
            && in_array($case->status, CaseModel::LOCKED_STATUSES, true)
            && ! $isAdmin) {
            throw new EvidenceProtectionException('Only an administrator can close or archive a case.');
        }

        if (in_array($case->getOriginal('status'), CaseModel::LOCKED_STATUSES, true)) {
            $onlyStatus = array_diff(array_keys($case->getDirty()), ['status', 'updated_at']) === [];

            if (! ($onlyStatus && $isAdmin)) {
                throw new EvidenceProtectionException('This case is closed. Only an administrator can reopen it.');
            }
        }
    }

    public function updated(CaseModel $case): void
    {
        $changes = [];

        foreach (self::TRACKED as $field) {
            if (array_key_exists($field, $case->getChanges())) {
                $changes[$field] = [
                    'from' => $case->getOriginal($field),
                    'to' => $case->getAttribute($field),
                ];
            }
        }

        if ($changes === []) {
            return;
        }

        if (isset($changes['photographer_id'])) {
            $old = $changes['photographer_id']['from'];
            $oldName = $old ? User::find($old)?->name : null;
            // Look the user up directly: the eager-loaded relation still holds the previous photographer.
            $newName = User::find($case->photographer_id)?->name;

            $this->log($case, 'REASSIGNED', sprintf(
                'Case reassigned from %s to %s.',
                $oldName ?? "Photographer ID {$old}",
                $newName ?? "Photographer ID {$case->photographer_id}",
            ), [
                'previous_photographer_id' => $old,
                'new_photographer_id' => $case->photographer_id,
                'reason' => $case->auditReason,
            ]);
            unset($changes['photographer_id']);
        }

        if (isset($changes['status'])) {
            $this->log($case, 'STATUS_CHANGED',
                "Status changed from {$changes['status']['from']} to {$changes['status']['to']}.",
                ['changes' => ['status' => $changes['status']], 'reason' => $case->auditReason]
            );
            unset($changes['status']);
        }

        if ($changes !== []) {
            $this->log($case, 'CASE_UPDATED',
                'Case details updated: '.implode(', ', array_keys($changes)).'.',
                ['changes' => $changes]
            );
        }
    }

    public function deleting(CaseModel $case): void
    {
        throw new EvidenceProtectionException('Cases cannot be deleted. Close or archive them instead.');
    }

    public static function nextSceneReference(?int $year = null): string
    {
        $year ??= (int) now()->format('Y');

        $max = CaseModel::query()
            ->where('scene_reference_number', 'like', "%/{$year}")
            ->pluck('scene_reference_number')
            ->map(fn ($ref) => preg_match('/STUDIOS\s+(\d+)\//i', (string) $ref, $m) ? (int) $m[1] : 0)
            ->max() ?? 0;

        return sprintf('STUDIOS %02d/%d', $max + 1, $year);
    }

    private function log(CaseModel $case, string $action, string $description, array $metadata = []): void
    {
        CaseLog::create([
            'case_id' => $case->getKey(),
            'user_id' => Auth::id(),
            'action' => $action,
            'role' => Auth::user()?->role ?? 'SYSTEM',
            'description' => $description,
            'metadata' => $metadata ?: null,
            'ip_address' => request()->ip(),
        ]);
    }
}
