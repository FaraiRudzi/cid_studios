<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CaseModel;

class PhotographerCaseController extends Controller
{
    public function __construct()
    {
        // Protect this controller with photographer guard
        $this->middleware('auth:photographer');
    }

    /**
     * Update the cause of death for a case.
     */
    public function updateCauseOfDeath(Request $request, CaseModel $case)
    {
        // Ensure this case belongs to the logged-in photographer
        if ($case->photographer_id !== auth()->guard('photographer')->id()) {
            abort(403, 'Unauthorized access to this case.');
        }

        // Validate the request
        $request->validate([
            'cause_of_death' => 'required|string|max:255',
        ]);

        // Update the case
        $case->update([
            'cause_of_death' => $request->input('cause_of_death'),
        ]);

        return back()->with('success', 'Cause of death updated successfully.');
    }
}
