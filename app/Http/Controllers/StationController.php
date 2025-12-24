<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Station;

class StationController extends Controller
{
   // In app/Http/Controllers/StationController.php

public function index(Request $request)
{
    $query = Station::query();

    // If there is a search term, apply the filter
    if ($request->filled('search')) {
        $query->where('name', 'like', '%' . $request->search . '%');
    }
    
    // Get the 'per_page' value from the request, default to 10
    $perPage = $request->input('per_page', 10);

    // Paginate the results
    $stations = $query->latest()->paginate($perPage);

    return view('admin.stations.index', compact('stations'));
}

    public function create()
    {
        return view('admin.stations.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:stations,name|max:255',
        ]);

        Station::create([
            'name' => $request->name,
        ]);

        return redirect()->route('admin.stations.index')->with('success', 'Station created successfully.');
    }

    public function show($id)
    {
        $station = Station::findOrFail($id);
        return view('admin.stations.show', compact('station'));
    }

    public function edit($id)
    {
        $station = Station::findOrFail($id);
        return view('admin.stations.edit', compact('station'));
    }

    public function update(Request $request, $id)
    {
        $station = Station::findOrFail($id);

        $request->validate([
            'name' => 'required|max:255|unique:stations,name,' . $station->id,
        ]);

        $station->update([
            'name' => $request->name,
        ]);

        return redirect()->route('admin.stations.index');
    }

    public function destroy($id)
    {
        $station = Station::findOrFail($id);
        $station->delete();

        return redirect()->route('admin.stations.index');
    }
}
