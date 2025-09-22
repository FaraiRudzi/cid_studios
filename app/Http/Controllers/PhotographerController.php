<?php

namespace App\Http\Controllers;

use App\Models\Photographer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use App\Rules\ForceNumber; // <-- IMPORT THE NEW RULE

class PhotographerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // ... (index logic remains the same)
        $query = Photographer::query();
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")->orWhere('surname', 'like', "%{$search}%")->orWhere('email', 'like', "%{$search}%")->orWhere('force_number', 'like', "%{$search}%");
            });
        }
        $query->withCount(['cases as total_cases_count', 'cases as pending_cases_count' => fn($q) => $q->whereNull('cause_of_death'), 'cases as finalised_cases_count' => fn($q) => $q->whereNotNull('cause_of_death')]);
        $perPage = $request->input('per_page', 10);
        $photographers = $query->latest()->paginate($perPage);
        return view('admin.photographers.index', compact('photographers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.photographers.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'surname' => ['required', 'string', 'max:255'],
            'phone_number' => ['required', 'string', 'max:255', 'unique:photographers,phone_number'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:photographers,email'],
            // USE THE NEW RULE HERE
            'force_number' => ['required', 'string', 'unique:photographers,force_number', new ForceNumber],
            'username' => ['required', 'string', 'max:255', 'unique:photographers,username'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $validatedData['password'] = Hash::make($request->password);
        Photographer::create($validatedData);

        return redirect()->route('admin.photographers.index')->with('success', 'Photographer created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Photographer $photographer)
    {
        return view('admin.photographers.show', compact('photographer'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Photographer $photographer)
    {
        return view('admin.photographers.edit', compact('photographer'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Photographer $photographer)
    {
        $validatedData = $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'surname' => ['required', 'string', 'max:255'],
            'phone_number' => ['required', 'string', 'max:255', 'unique:photographers,phone_number,' . $photographer->id],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:photographers,email,' . $photographer->id],
            // AND USE THE NEW RULE HERE
            'force_number' => ['required', 'string', 'unique:photographers,force_number,' . $photographer->id, new ForceNumber],
            'username' => ['required', 'string', 'max:255', 'unique:photographers,username,' . $photographer->id],
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
        ]);

        if (empty($validatedData['password'])) {
            unset($validatedData['password']);
        } else {
            $validatedData['password'] = Hash::make($validatedData['password']);
        }

        $photographer->update($validatedData);

        return redirect()->route('admin.photographers.index')->with('success', 'Photographer updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Photographer $photographer)
    {
        $photographer->delete();
        return redirect()->route('admin.photographers.index')->with('success', 'Photographer deleted successfully.');
    }
}