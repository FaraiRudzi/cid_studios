@extends('layouts.app')

@section('title', 'Edit Case')

@section('content')
<div class="container mx-auto">
    {{-- Header --}}
    <div class="max-w-4xl mx-auto mb-6 flex justify-between items-center">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-200">Edit Case: #{{ $case->scene_reference_number }}</h1>
        <a href="{{ route('admin.cases.show', $case->id) }}" class="text-blue-600 dark:text-blue-400 hover:underline flex items-center gap-2"><i data-feather="arrow-left" class="w-5 h-5"></i> Back to Case Details</a>
    </div>

    {{-- Form Card --}}
    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md max-w-4xl mx-auto">
        {{-- Initialize Alpine.js --}}
        <form action="{{ route('admin.cases.update', $case->id) }}" method="POST" x-data="{ case_type: '{{ old('case_type', $case->case_type) }}' }">
            @csrf
            @method('PUT')
            
            {{-- Core Info Section --}}
            {{-- This part allows an admin to reassign the photographer --}}
            @can('reassign', $case)
            <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 border-b dark:border-gray-700 pb-2">Admin Actions</h3>
            <div>
                <label for="photographer_id" class="block font-semibold text-gray-700 dark:text-gray-300 mb-1">Re-assign Photographer</label>
                <select name="photographer_id" id="photographer_id" required>
                    @foreach($photographers as $photographer)
                    <option value="{{ $photographer->id }}" @selected(old('photographer_id', $case->photographer_id) == $photographer->id)>{{ $photographer->fullName }}</option>
                    @endforeach
                </select>
            </div>
            @endcan

            {{-- THE FIX IS HERE: The rest of the form is only editable by the assigned photographer --}}
            @can('photographerUpdate', $case)
                {{-- Core Info Section --}}
                <div class="space-y-6">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 border-b dark:border-gray-700 pb-2">Core Information</h3>
                    {{-- Form fields for station, circumstances, etc. go here, disabled for admin --}}
                </div>

                {{-- DYNAMIC PERSON FIELDS --}}
                {{-- Each component is passed the correct person object from the case --}}
                <div x-show="['Murder', 'Sudden Death', 'Exhibits', 'Dying Declarations', 'Exhumations'].includes(case_type)" x-cloak>
                    <x-person-fields prefix="informant" label="Informant Details" :person="$case->informant" />
                </div>
                <div x-show="['Murder', 'Sudden Death'].includes(case_type)" x-cloak>
                    <x-person-fields prefix="deceased" label="Deceased Details" :person="$case->deceased" />
                </div>
                {{-- etc. for accused and complainant... --}}
            @endcan
            
            {{-- Form Actions --}}
            <div class="pt-6 flex items-center gap-4 border-t dark:border-gray-700 mt-6">
                <button type="submit" class="bg-blue-600 text-white px-5 py-2 rounded-md hover:bg-blue-700 font-semibold">Update Case</button>
                <a href="{{ route('admin.cases.show', $case->id) }}" class="text-gray-600 dark:text-gray-400 hover:underline">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection