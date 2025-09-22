<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\CaseModel; // Assuming your model is named CaseModel
use App\Models\Photographer;
use App\Models\Station;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
public function index()
{
    // --- Stat Cards Data ---
    $totalStations = Station::count();
    $totalPhotographers = Photographer::count();
    
    // We'll define a "finalised" case as one where 'cause_of_death' is not null.
    // Ensure your Case model is named correctly here (e.g., CaseModel or Case)
    $finalisedCases = CaseModel::whereNotNull('cause_of_death')->count();
    $pendingCases = CaseModel::whereNull('cause_of_death')->count();

    // The query for 'Cases per Station' has been removed.

    // --- Pass only the necessary data to the view ---
    return view('admin.dashboard', compact(
        'totalStations',
        'totalPhotographers',
        'finalisedCases',
        'pendingCases'
    ));
}
}