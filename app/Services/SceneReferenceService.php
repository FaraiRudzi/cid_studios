<<<<<<< HEAD
<?php

namespace App\Services;

use App\Models\CaseModel; // Or use App\Models\Case;
use Carbon\Carbon;

class SceneReferenceService
{
    /**
     * Generate the next sequential scene reference number for the current year.
     * Format: STUDIOS XX/YYYY
     *
     * @return string
     */
    public function generate(): string
    {
        // 1. Get the current year.
        $year = Carbon::now()->year;

        // 2. Find the most recently created case in the current year that has a scene reference number.
        $lastCase = CaseModel::whereYear('created_at', $year) // Or Case::class
                               ->whereNotNull('scene_reference_number')
                               ->orderBy('id', 'desc')
                               ->first();
        
        $nextNumber = 1;

        // 3. If a previous case was found, calculate the next number.
        if ($lastCase) {
            // Example: "STUDIOS 05/2025" -> parts[1] will be "05/2025"
            $parts = explode(' ', $lastCase->scene_reference_number);
            
            // "05/2025" -> numberPart will be "05"
            $numberPart = explode('/', $parts[1])[0];
            
            // Convert "05" to an integer (5) and add 1 to get 6.
            $nextNumber = intval($numberPart) + 1;
        }

        // 4. Format the final reference number.
        // sprintf("STUDIOS %02d/%d", 6, 2025) will result in "STUDIOS 06/2025"
        return sprintf("STUDIOS %02d/%d", $nextNumber, $year);
    }
=======
<?php

namespace App\Services;

use App\Models\CaseModel; // Or use App\Models\Case;
use Carbon\Carbon;

class SceneReferenceService
{
    /**
     * Generate the next sequential scene reference number for the current year.
     * Format: STUDIOS XX/YYYY
     *
     * @return string
     */
    public function generate(): string
    {
        // 1. Get the current year.
        $year = Carbon::now()->year;

        // 2. Find the most recently created case in the current year that has a scene reference number.
        $lastCase = CaseModel::whereYear('created_at', $year) // Or Case::class
                               ->whereNotNull('scene_reference_number')
                               ->orderBy('id', 'desc')
                               ->first();
        
        $nextNumber = 1;

        // 3. If a previous case was found, calculate the next number.
        if ($lastCase) {
            // Example: "STUDIOS 05/2025" -> parts[1] will be "05/2025"
            $parts = explode(' ', $lastCase->scene_reference_number);
            
            // "05/2025" -> numberPart will be "05"
            $numberPart = explode('/', $parts[1])[0];
            
            // Convert "05" to an integer (5) and add 1 to get 6.
            $nextNumber = intval($numberPart) + 1;
        }

        // 4. Format the final reference number.
        // sprintf("STUDIOS %02d/%d", 6, 2025) will result in "STUDIOS 06/2025"
        return sprintf("STUDIOS %02d/%d", $nextNumber, $year);
    }
>>>>>>> a8717e90389f512703bc262ebef9ebc076432c80
}