@extends('layouts.app')

@section('title', 'Add New Case')

@section('content')
<div class="container mx-auto">
    {{-- Header --}}
    <div class="max-w-4xl mx-auto mb-6 flex justify-between items-center">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-200">Add New Case</h1>
        <a href="{{ route('admin.cases.index') }}" class="text-blue-600 dark:text-blue-400 hover:underline flex items-center gap-2">
            <i data-feather="arrow-left" class="w-5 h-5"></i> Back to Case List
        </a>
    </div>

    {{-- Form Card --}}
    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md max-w-4xl mx-auto">
        {{-- Initialize Alpine.js, setting case_type from old input if it exists --}}
        <form action="{{ route('admin.cases.store') }}" method="POST" x-data="{ case_type: '{{ old('case_type', '') }}' }">
            @csrf
            
            {{-- Section 1: Core Case Info --}}
            <div class="space-y-6">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 border-b dark:border-gray-700 pb-2">Core Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="case_type" class="block font-semibold text-gray-700 dark:text-gray-300 mb-1">Case Type</label>
                        <select name="case_type" id="case_type" x-model="case_type" required class="form-select">
                            <option value="">-- Choose Case Type --</option>
                            @foreach($caseTypes as $type)
                                <option value="{{ $type }}" @selected(old('case_type') == $type)>{{ $type }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="station_id" class="block font-semibold text-gray-700 dark:text-gray-300 mb-1">Station</label>
                        <select name="station_id" id="station_id" required>
                            <option value="">-- Choose Station --</option>
                            @foreach($stations as $station)
                                <option value="{{ $station->id }}" @selected(old('station_id') == $station->id)>{{ $station->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="md:col-span-2">
                        <label for="reference_number" class="block font-semibold text-gray-700 dark:text-gray-300 mb-1">Case Reference Number</label>
                        <input type="text" id="reference_number" name="reference_number" value="{{ old('reference_number') }}" required class="form-input" placeholder="e.g. CID/MURDER/01/01/2025">
                    </div>

                    <div class="md:col-span-2">
                        <label for="circumstances" class="block font-semibold text-gray-700 dark:text-gray-300 mb-1">Circumstances</label>
                        <textarea id="circumstances" name="circumstances" rows="4" required class="form-textarea" placeholder="Brief background of the case">{{ old('circumstances') }}</textarea>
                    </div>
                    <div>
                        <label for="photographer_id" class="block font-semibold text-gray-700 dark:text-gray-300 mb-1">Assign Photographer</label>
                        <select name="photographer_id" id="photographer_id" required>
                            <option value="">-- Choose Photographer --</option>
                            @foreach($photographers as $photographer)
                                <option value="{{ $photographer->id }}" @selected(old('photographer_id') == $photographer->id)>
                                    {{ $photographer->surname }} ({{ $photographer->force_number }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div x-show="['Murder', 'Sudden Death'].includes(case_type)" x-cloak>
                        <label for="cause_of_death" class="block font-semibold text-gray-700 dark:text-gray-300 mb-1">Cause of Death (Optional)</label>
                        <input type="text" id="cause_of_death" name="cause_of_death" value="{{ old('cause_of_death') }}" class="form-input">
                    </div>
                </div>
            </div>

            {{-- DYNAMIC PERSON FIELDS --}}
            <div x-show="['Murder', 'Sudden Death', 'Exhibits', 'Dying Declarations', 'Exhumations'].includes(case_type)" x-cloak>
                <x-person-fields prefix="informant" label="Informant Details" />
            </div>
            
            <div x-show="['Murder', 'Sudden Death'].includes(case_type)" x-cloak>
                <x-person-fields prefix="deceased" label="Deceased Details (if known)" />
            </div>

            <div x-show="['Identification Parade', 'Indications', 'Warned and Cautioned Statement', 'Murder', 'Exhibits'].includes(case_type)" x-cloak>
                <x-person-fields prefix="accused" label="Accused Details" />
            </div>

            <div x-show="['Identification Parade', 'Exhibits'].includes(case_type)" x-cloak>
                <x-person-fields prefix="complainant" label="Complainant Details" />
            </div>
            
            {{-- Form Actions --}}
            <div class="pt-6 flex items-center gap-4 border-t dark:border-gray-700 mt-6">
                <button type="submit" class="bg-blue-600 text-white px-5 py-2 rounded-md hover:bg-blue-700 font-semibold flex items-center gap-2">
                    <i data-feather="save" class="w-5 h-5"></i>
                    Save Case
                </button>
                <a href="{{ route('admin.cases.index') }}" class="text-gray-600 dark:text-gray-400 hover:underline">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection {{-- This is the end of the content section --}}


{{-- This block contains all the necessary scripts for this page --}}
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize TomSelect for searchable dropdowns
        new TomSelect("#station_id",{ create: false, sortField: { field: "text", direction: "asc" } });
        new TomSelect("#photographer_id",{ create: false, sortField: { field: "text", direction: "asc" } });

        // THE FIX IS HERE: This will display validation errors in a SweetAlert pop-up
        @if ($errors->any())
            Swal.fire({
                icon: 'error',
                title: 'Validation Error',
                html: `{!! implode('<br>', $errors->all()) !!}`
            });
        @endif
    });
</script>

{{-- Define base styles for form inputs for consistency --}}
<style>
    .form-input, .form-select, .form-textarea { width: 100%; border-radius: 0.375rem; border: 1px solid #D1D5DB; box-shadow: 0 1px 2px 0 rgb(0 0 0 / 0.05); }
    .dark .form-input, .dark .form-select, .dark .form-textarea { background-color: #374151; border-color: #4B5563; color: #D1D5DB; }
    .ts-wrapper { width: 100%; }
</style>
@endpush

