<?php

namespace App\Http\Controllers;

use App\Models\CaseModel;
use App\Models\Media;
use App\Models\Person;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class PhotographerDashboardController extends Controller
{
    /**
     * Create a new controller instance and apply the photographer auth guard.
     */
    public function __construct()
    {
        $this->middleware('auth:photographer');
    }

    /**
     * Display the photographer's dashboard with their assigned cases.
     */
    public function index(Request $request)
    {
        $photographerId = Auth::id();
        $query = CaseModel::where('photographer_id', $photographerId)->with('station');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('reference_number', 'like', "%{$search}%")
                  ->orWhere('scene_reference_number', 'like', "%{$search}%")
                  ->orWhere('case_type', 'like', "%{$search}%")
                  ->orWhereHas('station', fn($q) => $q->where('name', 'like', "%{$search}%"));
            });
        }

        $perPage = $request->input('per_page', 10);
        $cases = $query->latest()->paginate($perPage);
        return view('photographer.dashboard', compact('cases'));
    }

    /**
     * Display the photographer's case workspace.
     */
    public function show(CaseModel $case)
    {
        if ($case->photographer_id !== Auth::id()) {
            abort(403, 'This action is unauthorized.');
        }
        $case->load(['people', 'station', 'media']);
        return view('photographer.case-details', compact('case'));
    }

    /**
     * Update the case details and associated people WITHOUT finalizing.
     */
    public function update(Request $request, CaseModel $case)
    {
        if ($case->photographer_id !== Auth::id()) { abort(403); }
        $validated = $request->validate(['circumstances' => 'required|string']);
        $case->update($validated);
        return back()->with('success', 'Case circumstances have been saved.');
    }

  public function updatePerson(Request $request, CaseModel $case, Person $person)
    {
        if ($case->photographer_id !== Auth::id()) { abort(403); }

        // This validation is now simple and only applies to the person being edited.
        $validatedData = $request->validate([
            'first_name' => 'required|string|max:255',
            'surname' => 'required|string|max:255',
            'id_number' => 'nullable|string|max:50|unique:people,id_number,' . $person->id,
            'address' => 'nullable|string',
            'phone_number' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255|unique:people,email,' . $person->id,
        ]);

        $person->update($validatedData);
        return back()->with('success', $person->fullName . "'s details have been updated.");
    }

    /**
     * Finalize the case. This is a separate, final action.
     */
    public function finalize(Request $request, CaseModel $case)
    {
        if ($case->photographer_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }
        
        $finalData = $request->validate([
            'cause_of_death' => [
                Rule::requiredIf(in_array($case->case_type, ['Murder', 'Sudden Death'])),
                'nullable', 'string', 'max:255',
            ],
        ]);
        
        $finalValue = $finalData['cause_of_death'] ?? 'Finalized as per procedure';
        $case->update(['cause_of_death' => $finalValue]);
        
        return redirect()->route('photographer.dashboard')->with('success', 'Case has been finalized and locked.');
    }
    
    /**
     * Remove the specified media file.
     */
    public function destroyMedia(Media $media)
    {
        if ($media->case->photographer_id !== Auth::id()) {
            abort(403, 'This action is unauthorized.');
        }
        Storage::disk('public')->delete($media->path);
        $media->delete();
        return back()->with('success', 'Media file deleted successfully.');
    }
    
    /**
     * Helper method to update people details from a request with flexible validation.
     */
    protected function updatePeopleFromRequest(Request $request, CaseModel $case)
    {
        $peopleByRole = $case->people->keyBy('pivot.role');
        $editablePersonTypes = ['deceased', 'accused'];

        foreach ($editablePersonTypes as $type) {
            if (isset($peopleByRole[$type])) {
                if ($peopleByRole[$type] instanceof \Illuminate\Database\Eloquent\Collection) {
                    // This handles multiple accused people
                    foreach ($peopleByRole[$type] as $index => $person) {
                        $this->validateAndUpdatePerson($request, $person, "{$type}_{$index}");
                    }
                } else {
                    // This handles a single person (like deceased)
                    $person = $peopleByRole[$type];
                    $this->validateAndUpdatePerson($request, $person, $type);
                }
            }
        }
    }

    /**
     * A new, cleaner helper to validate and update a single person.
     */
    private function validateAndUpdatePerson(Request $request, Person $person, string $prefix)
    {
        // Flexible validation rules: name is required, the rest is optional.
        $rules = [
            "{$prefix}_first_name" => 'required|string|max:255',
            "{$prefix}_surname" => 'required|string|max:255',
            "{$prefix}_id_number" => 'nullable|string|max:50|unique:people,id_number,' . $person->id,
            "{$prefix}_address" => 'nullable|string',
            "{$prefix}_phone_number" => 'nullable|string|max:50',
            "{$prefix}_email" => 'nullable|email|max:255|unique:people,email,' . $person->id,
        ];

        $validatedData = $request->validate($rules);
        
        // Build a clean data array, only including fields that were actually submitted in the form.
        $updateData = [];
        $fields = ['first_name', 'surname', 'id_number', 'address', 'phone_number', 'email'];
        foreach ($fields as $field) {
            $prefixedField = "{$prefix}_{$field}";
            // Check if the field exists in the validated data from the request
            if (array_key_exists($prefixedField, $validatedData)) {
                $updateData[$field] = $validatedData[$prefixedField];
            }
        }

        // Update the person model with only the submitted data.
        if (!empty($updateData)) {
            $person->update($updateData);
        }
    }
}