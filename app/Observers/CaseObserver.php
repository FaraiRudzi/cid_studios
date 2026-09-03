<?php

namespace App\Observers;

use App\Models\CaseLog;
use App\Models\CaseModel;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class CaseObserver
{
    public function creating(CaseModel $case): void
    {
        $year = now()->format('Y');
        $latest = CaseModel::whereYear('created_at', $year)->latest('id')->first();

        $nextNumber = 1;
        if ($latest && preg_match('/STUDIOS\s+(\d+)\//i', $latest->scene_reference_number, $matches)) {
            $nextNumber = ((int) $matches[1]) + 1;
        }

        $case->scene_reference_number = sprintf('STUDIOS %02d/%s', $nextNumber, $year);
        $case->created_by = Auth::id() ?? 1;
    }

    public function created(CaseModel $case): void
    {
        CaseLog::create([
            'case_id' => $case->id,
            'user_id' => Auth::id(),
            'action' => 'CASE_CREATED',
            'role' => Auth::user()?->role ?? 'SYSTEM',
            'description' => "Case created and assigned to photographer (ID: {$case->photographer_id}).",
            'ip_address' => request()->ip(),
        ]);
    }

    public function updated(CaseModel $case): void
    {
        if ($case->isDirty('photographer_id')) {
            $oldPhotographerId = $case->getOriginal('photographer_id');
            $oldPhotographer = $oldPhotographerId ? User::find($oldPhotographerId) : null;
            $newPhotographer = $case->photographer;

            CaseLog::create([
                'case_id' => $case->id,
                'user_id' => Auth::id(),
                'action' => 'REASSIGNED',
                'role' => Auth::user()?->role ?? 'ADMIN',
                'description' => sprintf(
                    'Case reassigned from %s to %s.',
                    $oldPhotographer?->name ?? "Photographer ID {$oldPhotographerId}",
                    $newPhotographer?->name ?? "Photographer ID {$case->photographer_id}",
                ),
                'metadata' => [
                    'previous_photographer_id' => $oldPhotographerId,
                    'new_photographer_id' => $case->photographer_id,
                ],
                'ip_address' => request()->ip(),
            ]);
        }
    }
}
