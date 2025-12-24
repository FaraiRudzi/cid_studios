<?php

namespace App\Services;

use App\Models\CaseModel;
use Carbon\Carbon;

class SceneReferenceService
{
    /**
     * Generate the next sequential scene reference number for the current year.
     * Format: STUDIOS XX/YYYY
     */
    public function generate(): string
    {
        $year = Carbon::now()->year;

        $lastCase = CaseModel::whereYear('created_at', $year)
            ->whereNotNull('scene_reference_number')
            ->orderByDesc('id')
            ->first();

        $nextNumber = 1;

        if ($lastCase) {
            // Expected format: STUDIOS 05/2025
            if (preg_match('/STUDIOS\s(\d{2})\/\d{4}/', $lastCase->scene_reference_number, $matches)) {
                $nextNumber = (int) $matches[1] + 1;
            }
        }

        return sprintf('STUDIOS %02d/%d', $nextNumber, $year);
    }
}
