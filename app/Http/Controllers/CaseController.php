<?php

namespace App\Http\Controllers;

use App\Models\CaseModel;
use App\Models\Person;
use App\Models\Station;
use App\Models\Photographer;
use App\Services\SceneReferenceService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Barryvdh\DomPDF\Facade\Pdf;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;

class CaseController extends Controller
{
    /**
     * Display a listing of all cases for the admin.
     */
    public function index(Request $request)
    {
        $query = CaseModel::with(['station', 'photographer', 'people']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('reference_number', 'like', "%{$search}%")
                  ->orWhere('scene_reference_number', 'like', "%{$search}%")
                  ->orWhere('case_type', 'like', "%{$search}%")
                  ->orWhereHas('station', fn($q) => $q->where('name', 'like', "%{$search}%"))
                  ->orWhereHas('photographer', fn($q) => $q->where('first_name', 'like', "%{$search}%")->orWhere('surname', 'like', "%{$search}%"));
            });
        }

        $perPage = $request->input('per_page', 10);
        $cases = $query->latest()->paginate($perPage);

        return view('admin.cases.index', compact('cases'));
    }

    /**
     * Show the form for creating a new case.
     */
    public function create()
    {
        $stations = Station::orderBy('name')->get();
        $photographers = Photographer::orderBy('first_name')->get();
        $caseTypes = ['Murder', 'Sudden Death', 'Identification Parade', 'Indications', 'Warned and Cautioned Statement', 'Exhibits', 'Dying Declarations', 'Exhumations'];
        
        return view('admin.cases.create', compact('stations', 'photographers', 'caseTypes'));
    }

    /**
     * Store a newly created case in storage.
     */
    public function store(Request $request, SceneReferenceService $referenceService)
    {
        $coreValidated = $request->validate([
            'station_id' => 'required|exists:stations,id',
            'photographer_id' => 'required|exists:photographers,id',
            'case_type' => 'required|string|max:255',
            'reference_number' => 'required|string|max:255|unique:cases,reference_number',
            'circumstances' => 'required|string',
            'cause_of_death' => 'nullable|string|max:255',
        ]);
        
        $coreValidated['scene_reference_number'] = $referenceService->generate();
        $case = CaseModel::create($coreValidated);

        $this->syncPeopleFromRequest($request, $case);
        
        return redirect()->route('admin.cases.index')
                         ->with('success', 'Case created with Scene Ref: ' . $case->scene_reference_number);
    }

    /**
     * Display the specified case details.
     */
    public function show(CaseModel $case)
    {
        $case->load(['people', 'station', 'photographer', 'media']);
        return view('admin.cases.show', compact('case'));
    }

    /**
     * Show the form for editing the specified case.
     */
    public function edit(CaseModel $case)
    {
        $case->load(['people']);
        $stations = Station::orderBy('name')->get();
        $photographers = Photographer::orderBy('first_name')->get();
        $caseTypes = ['Murder', 'Sudden Death', 'Identification Parade', 'Indications', 'Warned and Cautioned Statement', 'Exhibits', 'Dying Declarations', 'Exhumations'];
        
        return view('admin.cases.edit', compact('case', 'stations', 'photographers', 'caseTypes'));
    }

    /**
     * Update the specified case in storage.
     */
    public function update(Request $request, CaseModel $case)
    {
        // $this->authorize('update', $case); 

        $validatedData = $request->validate([
            'station_id' => 'required|exists:stations,id',
            'photographer_id' => 'required|exists:photographers,id',
            'case_type' => 'required|string|max:255',
            'reference_number' => 'required|string|max:255|unique:cases,reference_number,' . $case->id,
            'circumstances' => 'required|string',
            'cause_of_death' => 'nullable|string|max:255',
        ]);

        $case->update($validatedData);
        $this->syncPeopleFromRequest($request, $case);

        return redirect()->route('admin.cases.show', $case)->with('success', 'Case details updated successfully.');
    }

    /**
     * Reassign the photographer for a case.
     */
    public function reassign(Request $request, CaseModel $case)
    {
        // $this->authorize('reassign', $case);

        $validated = $request->validate(['photographer_id' => 'required|exists:photographers,id']);
        $case->update($validated);
        // TODO: Log this change to the case_assignment_histories table
        
        return redirect()->route('admin.cases.show', $case)->with('success', 'Photographer reassigned successfully.');
    }

    /**
     * Remove the specified case from storage.
     */
    public function destroy(CaseModel $case)
    {
        // $this->authorize('delete', $case);
        $case->delete();
        return redirect()->route('admin.cases.index')->with('success', 'Case deleted successfully.');
    }
    
    /**
     * Generate and export a PDF document for the specified case.
     */
    
    // In app/Http/Controllers/CaseController.php

/**
 * Generate and export a PDF document for the specified case.
 */
// In app/Http/Controllers/CaseController.php

public function exportCasePdf(Request $request, CaseModel $case)
{
    // Step 1: Eager load all necessary relationships.
    $case->load(['station', 'photographer', 'people', 'media']);

    // Step 2: Validate that the user has selected which media to include.
    $validated = $request->validate([
        'media_ids' => 'required|array|min:1',
        'media_ids.*' => 'exists:media,id',
    ]);

    // Step 3: Fetch ONLY the selected media items, preserving their order.
    $mediaItems = $case->media()->whereIn('id', $validated['media_ids'])->get();

    // Step 4: Prepare the QR Code data.
    $qrData = "Scene Ref: {$case->scene_reference_number}\n" .
              "Case Ref: {$case->reference_number}\n" .
              "Photographer: {$case->photographer?->force_number} {$case->photographer?->first_name} {$case->photographer?->surname}\n".
              "Contact: {$case->photographer?->phone_number}";

    // Step 5: Generate the QR Code as a base64-encoded SVG.
    $renderer = new ImageRenderer(new RendererStyle(140), new SvgImageBackEnd());
    $writer = new Writer($renderer);
    $qrCodeSvg = $writer->writeString($qrData);
    $qrCodeBase64 = 'data:image/svg+xml;base64,' . base64_encode($qrCodeSvg);

    // Step 6: Load the PDF view with all the necessary data.
    $pdf = PDF::loadView('admin.cases.export-pdf', [
        'case' => $case,
        'mediaItems' => $mediaItems,
        'qrCode' => $qrCodeBase64,
    ]);

    // Step 7: Configure and stream the PDF.
    $pdf->setPaper('a4', 'portrait');
    $safeReferenceNumber = str_replace('/', '-', $case->scene_reference_number);
    return $pdf->stream('exhibit-' . $safeReferenceNumber . '.pdf');
}

    /**
     * Helper function to process and sync people from a request.
     */
   protected function syncPeopleFromRequest(Request $request, CaseModel $case)
{
    $case->people()->detach();
    $personTypes = ['informant', 'deceased', 'accused', 'complainant'];

    foreach ($personTypes as $type) {
        // --- THE FIX IS HERE ---
        // We only need to check if a primary field like 'first_name' was submitted.
        if ($request->filled("{$type}_first_name")) {
            
            // Validate the incoming data for this person. All fields are nullable.
            $validatedData = $request->validate([
                "{$type}_first_name" => 'nullable|string|max:255',
                "{$type}_surname" => 'nullable|string|max:255',
                "{$type}_id_number" => 'nullable|string|max:50',
                "{$type}_address" => 'nullable|string',
                "{$type}_phone_number" => 'nullable|string|max:50',
                "{$type}_email" => 'nullable|email|max:255',
            ]);
            
            // Create a new person record with only the data that was actually provided.
            $person = Person::create([
                'first_name' => $request->input("{$type}_first_name"),
                'surname' => $request->input("{$type}_surname"),
                'id_number' => $request->input("{$type}_id_number"),
                'address' => $request->input("{$type}_address"),
                'phone_number' => $request->input("{$type}_phone_number"),
                'email' => $request->input("{$type}_email"),
            ]);

            $case->people()->attach($person->id, ['role' => $type]);
        }
    }
}
}