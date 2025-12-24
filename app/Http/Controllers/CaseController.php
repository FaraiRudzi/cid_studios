<?php

namespace App\Http\Controllers;

use App\Models\{CaseModel, Person, Station, Photographer, Media};
use App\Services\SceneReferenceService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, Storage};
use Barryvdh\DomPDF\Facade\Pdf;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;

class CaseController extends Controller
{
    // ======================================================================
    // ADMIN ACTIONS
    // ======================================================================

    public function index(Request $request)
    {
        $query = CaseModel::with(['station', 'photographer', 'people']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('reference_number', 'like', "%{$search}%")
                  ->orWhere('scene_reference_number', 'like', "%{$search}%")
                  ->orWhereHas('station', fn($sq) => $sq->where('name', 'like', "%{$search}%"))
                  ->orWhereHas('photographer', fn($pq) => $pq->where('surname', 'like', "%{$search}%"));
            });
        }

        return view('admin.cases.index', [
            'cases' => $query->latest()->paginate($request->input('per_page', 10))
        ]);
    }

    public function create()
    {
        return view('admin.cases.create', [
            'stations'      => Station::orderBy('name')->get(),
            'photographers' => Photographer::orderBy('surname')->get(),
            'caseTypes'     => ['Murder', 'Sudden Death', 'ID Parade', 'Indications', 'Other']
        ]);
    }

    public function store(Request $request, SceneReferenceService $referenceService)
    {
        $validated = $request->validate([
            'station_id'       => 'required|exists:stations,id',
            'photographer_id'  => 'required|exists:photographers,id',
            'case_type'        => 'required|string',
            'manual_offence'   => 'nullable|string|required_if:case_type,Other,Indications',
            'reference_number' => 'required|string|unique:cases,reference_number',
            'circumstances'    => 'required|string',
        ]);

        if (in_array($request->case_type, ['Other', 'Indications']) && $request->filled('manual_offence')) {
            $validated['case_type'] = strtoupper($request->manual_offence);
        }

        $validated['scene_reference_number'] = $referenceService->generate();
        $case = CaseModel::create($validated);

        $this->syncPeopleFromRequest($request, $case);

        $case->recordLog('CREATED', "Forensic dossier opened. Assigned to: {$case->photographer->surname}");

        return redirect()->route('admin.cases.index')->with('success', 'Case created: ' . $case->scene_reference_number);
    }

    public function show(CaseModel $case)
    {
        $case->load(['people', 'station', 'photographer', 'media', 'logs.user']);
        return view('admin.cases.show', [
            'case'          => $case,
            'photographers' => Photographer::orderBy('surname')->get()
        ]);
    }

    public function update(Request $request, CaseModel $case)
    {
        $oldType = $case->case_type;
        $validated = $request->validate([
            'station_id'       => 'required|exists:stations,id',
            'photographer_id'  => 'required|exists:photographers,id',
            'case_type'        => 'required|string',
            'reference_number' => 'required|string|unique:cases,reference_number,' . $case->id,
            'circumstances'    => 'required|string',
            'cause_of_death'   => 'nullable|string',
        ]);

        $case->update($validated);
        $this->syncPeopleFromRequest($request, $case);

        if ($oldType !== $case->case_type) {
            $case->recordLog('EVOLVED', "Type changed from {$oldType} to {$case->case_type}.");
        }

        return redirect()->route('admin.cases.show', $case)->with('success', 'Dossier updated.');
    }

    // ======================================================================
    // PHOTOGRAPHER ACTIONS
    // ======================================================================

    public function photographerIndex(Request $request)
    {
        $query = CaseModel::where('photographer_id', Auth::guard('photographer')->id())->with('station');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('reference_number', 'like', "%{$search}%")
                  ->orWhere('scene_reference_number', 'like', "%{$search}%");
            });
        }

        return view('photographer.dashboard', [
            'cases' => $query->latest()->paginate(10)
        ]);
    }

    public function photographerShow(CaseModel $case)
{
    if ($case->photographer_id !== Auth::guard('photographer')->id()) {
        abort(403);
    }

    // Use with('logs') to ensure they are ready for the view
    $case->load(['people', 'station', 'media', 'logs']);

    return view('photographer.show', compact('case'));
}


    public function photographerUpdate(Request $request, CaseModel $case)
    {
        $request->validate(['circumstances' => 'required|string']);
        $case->update(['circumstances' => $request->circumstances]);

        $case->recordLog('UPDATE', 'Brief circumstances modified by photographer.');
        return back()->with('success', 'Circumstances updated.');
    }

    public function transition(Request $request, CaseModel $case)
    {
        $oldType = $case->case_type;
        $newType = $request->case_type;

        $case->update([
            'case_type' => $newType,
            'cause_of_death' => $request->cause_of_death
        ]);

        $details = "Cause of death: " . ($request->cause_of_death ?? 'Pending');

        if ($oldType !== $newType) {
            $case->recordLog('EVOLVED', "Case type upgraded from $oldType to $newType. $details");
        } else {
            $case->recordLog('UPDATE', "Forensic findings updated. $details");
        }

        return back()->with('success', 'Case status and findings updated.');
    }

    public function deleteMedia(CaseModel $case, Media $media)
    {
        $filePath = $media->path;

        // 1. Remove from Physical Storage
        if (Storage::disk('public')->exists($filePath)) {
            Storage::disk('public')->delete($filePath);
        }

        // 2. Remove from Database
        $media->delete();

        // 3. Log Action
        $case->recordLog('DELETED', "Forensic exhibit removed: " . basename($filePath));

        return back()->with('success', 'Evidence purged from dossier.');
    }

    public function updatePerson(Request $request, CaseModel $case, Person $person)
    {
        $validated = $request->validate([
            'first_name'   => 'required|string|max:255',
            'surname'      => 'required|string|max:255',
            'id_number'    => 'required|string|max:50',
            'phone_number' => 'nullable|string|max:20',
            'address'      => 'nullable|string',
        ]);

        $person->update($validated);

        $case->recordLog('UPDATE', "Particulars for {$person->pivot->role} ({$person->surname}) updated.");

        return back()->with('success', "Subject details updated.");
    }

    public function uploadMedia(Request $request, CaseModel $case)
    {
        $request->validate([
            'category'    => 'required|string',
            'description' => 'required|string',
            'files.*'     => 'required|image|max:10240',
        ]);

        if($request->hasFile('files')) {
            $files = $request->file('files');
            foreach($files as $file) {
                $path = $file->store('forensic_evidence/' . $case->reference_number, 'public');

                $case->media()->create([
                    'path'        => $path,
                    'type'        => $request->category,
                    'description' => $request->description,
                ]);
            }

            $case->recordLog('UPLOAD', count($files) . " items secured under category: {$request->category}");
        }

        return back()->with('success', 'Media secured successfully!');
    }

    // ======================================================================
    // UTILITIES (EXPORTS & SYNC)
    // ======================================================================

    public function exportCasePdf(Request $request, CaseModel $case)
    {
        $validated = $request->validate([
            'media_ids' => 'required|array|min:1',
            'media_ids.*' => 'exists:media,id',
        ]);

        $mediaItems = $case->media()->whereIn('id', $validated['media_ids'])->get();

        $qrData = "Scene Ref: {$case->scene_reference_number}\nDate: " . now()->format('Y-m-d H:i');
        $renderer = new ImageRenderer(new RendererStyle(140), new SvgImageBackEnd());
        $writer = new Writer($renderer);
        $qrCodeBase64 = 'data:image/svg+xml;base64,' . base64_encode($writer->writeString($qrData));

        $case->recordLog('PRINTED', "Exhibit generated with " . count($mediaItems) . " items.");

        return PDF::loadView('admin.cases.export-pdf', compact('case', 'mediaItems', 'qrCodeBase64'))
                  ->stream("exhibit-{$case->scene_reference_number}.pdf");
    }

    protected function syncPeopleFromRequest(Request $request, CaseModel $case)
    {
        $personTypes = ['informant', 'deceased', 'accused', 'complainant'];
        $case->people()->detach();

        foreach ($personTypes as $type) {
            if ($request->filled("{$type}_surname") || $request->filled("{$type}_first_name")) {
                $person = Person::updateOrCreate(
                    ['id_number' => $request->input("{$type}_id_number") ?? 'UNKNOWN_' . uniqid()],
                    [
                        'surname'      => $request->input("{$type}_surname"),
                        'first_name'   => $request->input("{$type}_first_name"),
                        'address'      => $request->input("{$type}_address"),
                        'phone_number' => $request->input("{$type}_phone_number"),
                        'email'        => $request->input("{$type}_email"),
                    ]
                );
                $case->people()->attach($person->id, ['role' => $type]);
            }
        }
    }

    public function destroy(CaseModel $case)
    {
        $case->delete();
        return redirect()->route('admin.cases.index')->with('success', "Case purged from active records.");
    }
}
