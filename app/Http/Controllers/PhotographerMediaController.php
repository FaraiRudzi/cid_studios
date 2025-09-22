<?php

namespace App\Http\Controllers;

use App\Models\CaseModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class PhotographerMediaController extends Controller
{
    /**
     * Create a new controller instance and apply the photographer auth guard.
     */
    public function __construct()
    {
        $this->middleware('auth:photographer');
    }

    /**
     * Store a newly uploaded media file for a case.
     */
    public function store(Request $request, CaseModel $case)
    {
        // Step 1: Authorize the action
        // Ensure the case belongs to the logged-in photographer.
        if ($case->photographer_id !== Auth::id()) {
            abort(403, 'This action is unauthorized.');
        }

        // Step 2: Validate the incoming request
        $validated = $request->validate([
            // Use 'media_file' to avoid conflict with a potential 'media' relationship
            'media_file' => ['required', 'file', 'max:20480', 'mimes:jpg,jpeg,png,mp4,mov,avi'], // 20MB Max
            'type' => ['required', 'string', Rule::in(['image', 'video'])],
            'description' => ['required', 'string', 'max:255'],
        ]);

        // Step 3: Store the physical file
        // The file will be stored in: storage/app/public/media/{case_id}/[unique_filename]
        $filePath = $request->file('media_file')->store('media/' . $case->id, 'public');

        // Step 4: Create the record in the 'media' database table
        $case->media()->create([
            'path' => $filePath,
            'type' => $validated['type'],
            'description' => $validated['description'],
        ]);

        // Step 5: Redirect back with a success message
        return back()->with('success', 'Media uploaded successfully.');
    }
}