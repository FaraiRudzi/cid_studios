@extends('layouts.app')

@section('title', 'Create New Case')

@push('styles')
{{-- TomSelect CSS --}}
<link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css" rel="stylesheet">

<style>
    /* Standardized Form Styling */
    input[type="text"], input[type="email"], textarea, select:not(.ts-select) {
        @apply w-full bg-gray-50 dark:bg-gray-900 border-2 border-gray-200 dark:border-gray-700
               rounded-xl px-4 py-3 text-sm font-bold text-gray-800 dark:text-white
               transition-all outline-none !important;
    }

    input:hover, textarea:hover, .ts-control:hover {
        @apply border-blue-500 !important;
    }

    input:focus, textarea:focus, .ts-wrapper.focus .ts-control {
        @apply border-zrp-gold ring-4 ring-zrp-gold/10 bg-white dark:bg-gray-800 !important;
    }

    /* TomSelect Overrides */
    .ts-wrapper.ts-select .ts-control {
        @apply border-2 border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900
               rounded-xl px-4 py-3 flex items-center min-h-[48px] transition-all !important;
    }

    [x-cloak] { display: none !important; }
</style>
@endpush

@section('content')
<div class="max-w-5xl mx-auto pb-12">

    {{-- Top Navigation --}}
    <div class="flex justify-end mb-8 border-b-2 border-gray-200 pb-6">
        <a href="{{ route('admin.cases.index') }}"
           class="flex items-center gap-2 text-base font-bold text-zrp-blue hover:text-white hover:bg-zrp-blue px-4 py-2 rounded-lg uppercase italic transition-all">
            <span>Back to Case List</span>
            <i data-feather="arrow-left" class="w-5 h-5"></i>
        </a>
    </div>

    {{-- Main Form --}}
    <form action="{{ route('admin.cases.store') }}" method="POST"
          x-data="{
            case_type: '{{ old('case_type', '') }}',
            previous_type: '{{ old('case_type', '') }}',
            handleTypeChange(val) {
                if (this.previous_type === 'Sudden Death' && val === 'Murder') {
                    alert('UPGRADE NOTICE: This case is now classified as MURDER. Accused details and forensic indications are now mandatory.');
                }
                this.previous_type = val;
            }
          }"
          class="space-y-8">
        @csrf

        {{-- Section 1: Core Information --}}
        <div class="bg-white dark:bg-gray-800 shadow-2xl shadow-zrp-blue/5 border border-gray-100 dark:border-gray-700 overflow-hidden rounded-2xl">
            <div class="bg-zrp-blue px-8 py-4">
                <h3 class="text-white font-black text-xs uppercase tracking-[0.2em] flex items-center gap-2">
                    <i data-feather="file-text" class="w-4 h-4 text-zrp-gold"></i>
                    Dossier Core Information
                </h3>
            </div>

            <div class="p-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    {{-- Case Type Selector --}}
                    <div class="space-y-1.5">
                        <label for="case_type" class="block text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Case Classification</label>
                        <select name="case_type" id="case_type" x-model="case_type" required
                                @change="handleTypeChange($event.target.value)"
                                class="appearance-none">
                            <option value="">-- Select Type --</option>
                            <option value="Sudden Death">Sudden Death</option>
                            <option value="Murder">Murder</option>
                            <option value="ID Parade">ID Parade</option>
                            <option value="Indications">Indications</option>
                            <option value="Other">Other / Manual Entry</option>
                        </select>
                    </div>

                    {{-- Manual Offence Name (Visible for Other/Indications) --}}
                    <div x-show="['Other', 'Indications'].includes(case_type)" x-transition x-cloak class="space-y-1.5">
                        <label for="manual_offence" class="block text-[10px] font-black text-zrp-gold uppercase tracking-widest ml-1">Specific Offence Name</label>
                        <input type="text" id="manual_offence" name="manual_offence" value="{{ old('manual_offence') }}"
                               class="!border-zrp-gold"
                               placeholder="e.g. RAPE, UNLAWFUL ENTRY">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    {{-- Station --}}
                    <div class="space-y-1.5">
                        <label for="station_id" class="block text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Station</label>
                        <select name="station_id" id="station_id" required class="ts-select">
                            <option value="">Search station name...</option>
                            @foreach($stations as $station)
                                <option value="{{ $station->id }}" @selected(old('station_id') == $station->id)>{{ $station->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Station Reference --}}
                    <div class="space-y-1.5">
                        <label for="reference_number" class="block text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Station Reference (CR/RR No)</label>
                        <input type="text" id="reference_number" name="reference_number" value="{{ old('reference_number') }}" required
                               placeholder="e.g. CR 12/05/25">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    {{-- Photographer --}}
                    <div class="space-y-1.5">
                        <label for="photographer_id" class="block text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Assign Photographer</label>
                        <select name="photographer_id" id="photographer_id" required>
                            <option value="">-- Select Photographer --</option>
                            @foreach($photographers as $p)
                                <option value="{{ $p->id }}" @selected(old('photographer_id') == $p->id)>
                                    {{ $p->surname }} ({{ $p->force_number }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Cause of Death (Only for Death-related cases) --}}
                    <div x-show="['Murder', 'Sudden Death'].includes(case_type)" x-transition x-cloak class="space-y-1.5">
                        <label for="cause_of_death" class="block text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Cause of Death (If known)</label>
                        <input type="text" id="cause_of_death" name="cause_of_death" value="{{ old('cause_of_death') }}"
                               placeholder="Enter cause from post-mortem">
                    </div>
                </div>

                {{-- Circumstances --}}
                <div class="space-y-1.5">
                    <label for="circumstances" class="block text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Brief Circumstances</label>
                    <textarea id="circumstances" name="circumstances" rows="4" required
                              placeholder="Type detailed circumstances or indication details...">{{ old('circumstances') }}</textarea>
                </div>
            </div>
        </div>

        {{-- Section 2: Dynamic Personnel Sections --}}
        <div class="space-y-8">

            {{-- INFORMANT: Murder & Sudden Death --}}
            <template x-if="['Murder', 'Sudden Death'].includes(case_type)">
                <div class="bg-white dark:bg-gray-800 rounded-[2rem] p-8 shadow-sm border-2 border-dashed border-gray-100 dark:border-gray-700">
                    <x-person-fields prefix="informant" label="Informant Details" />
                </div>
            </template>

            {{-- DECEASED: Murder & Sudden Death --}}
            <template x-if="['Murder', 'Sudden Death'].includes(case_type)">
                <div class="bg-white dark:bg-gray-800 rounded-[2rem] p-8 shadow-sm border-2 border-dashed border-gray-100 dark:border-gray-700">
                    <x-person-fields prefix="deceased" label="Deceased Details" />
                </div>
            </template>

            {{-- COMPLAINANT: ID Parade & Other --}}
            <template x-if="['ID Parade', 'Other'].includes(case_type)">
                <div class="bg-white dark:bg-gray-800 rounded-[2rem] p-8 shadow-sm border-2 border-dashed border-gray-100 dark:border-gray-700">
                    <x-person-fields prefix="complainant" label="Complainant Details" />
                </div>
            </template>

            {{-- ACCUSED: Murder, ID Parade, Indications, Other --}}
            <template x-if="['Murder', 'ID Parade', 'Indications', 'Other'].includes(case_type)">
                <div class="bg-white dark:bg-gray-800 rounded-[2rem] p-8 shadow-sm border-2 border-zrp-gold/20">
                    <x-person-fields prefix="accused" label="Accused Details" />
                </div>
            </template>

        </div>

        {{-- Form Actions --}}
        <div class="flex justify-end items-center gap-4 mt-12">
            <a href="{{ route('admin.cases.index') }}"
               class="px-8 py-3 border-2 border-gray-300 text-gray-400 text-base font-bold uppercase italic rounded-lg hover:border-red-500 hover:text-red-500 transition-all">
                Cancel
            </a>

            <button type="submit"
                    class="flex items-center gap-2 px-10 py-3 border-2 border-zrp-blue text-zrp-blue text-base font-bold uppercase italic rounded-lg hover:bg-zrp-blue hover:text-white transition-all shadow-sm">
                <i data-feather="save" class="w-5 h-5"></i>
                Save Forensic Record
            </button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Station Searchable Dropdown
        new TomSelect("#station_id", {
            create: false,
            searchField: ['text'],
            placeholder: "Type to filter stations...",
        });

        // Initialize Icons
        if (typeof feather !== 'undefined') {
            feather.replace();
        }
    });
</script>
@endpush
