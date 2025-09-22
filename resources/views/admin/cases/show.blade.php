@extends('layouts.app')

@section('title', 'Case Details')

@section('content')
{{-- Initialize Alpine.js for the modal --}}
<div class="container mx-auto" x-data="{ isExportModalOpen: false }">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row justify-between sm:items-center gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-200">Case Details</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400">{{ $case->scene_reference_number }}</p>
        </div>
        <a href="{{ route('admin.cases.index') }}" class="text-blue-600 dark:text-blue-400 hover:underline flex items-center gap-2">
            <i data-feather="arrow-left" class="w-5 h-5"></i> Back to Case List
        </a>
    </div>

    <!-- Grid for main details -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        
        <!-- Card 1: Core Information -->
        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md">
            <h3 class="text-xl font-semibold mb-4 border-b dark:border-gray-700 pb-2 text-gray-800 dark:text-gray-200">Core Information</h3>
            <dl class="grid grid-cols-[auto,1fr] gap-x-6 gap-y-3 text-base">
                <dt class="font-semibold text-gray-600 dark:text-gray-400">Case Reference:</dt>
                <dd class="text-gray-900 dark:text-gray-100">{{ $case->reference_number }}</dd>
                <dt class="font-semibold text-gray-600 dark:text-gray-400">Station:</dt>
                <dd class="text-gray-900 dark:text-gray-100">{{ $case->station?->name ?? 'N/A' }}</dd>
                <dt class="font-semibold text-gray-600 dark:text-gray-400">Case Type:</dt>
                <dd class="text-gray-900 dark:text-gray-100">{{ $case->case_type }}</dd>
                <dt class="font-semibold text-gray-600 dark:text-gray-400">Cause of Death:</dt>
                <dd class="text-gray-900 dark:text-gray-100">{{ $case->cause_of_death ?? 'Not specified' }}</dd>
            </dl>
        </div>

        <!-- Card 2: Assigned Photographer -->
        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md">
             <h3 class="text-xl font-semibold mb-4 border-b dark:border-gray-700 pb-2 text-gray-800 dark:text-gray-200">Assigned Photographer</h3>
             <dl class="grid grid-cols-[auto,1fr] gap-x-6 gap-y-3 text-base">
                <dt class="font-semibold text-gray-600 dark:text-gray-400">Full Name:</dt>
                <dd class="text-gray-900 dark:text-gray-100">{{ $case->photographer?->first_name ?? 'N/A' }} {{ $case->photographer?->surname ?? 'N/A' }}</dd>
                <dt class="font-semibold text-gray-600 dark:text-gray-400">Force Number:</dt>
                <dd class="text-gray-900 dark:text-gray-100">{{ $case->photographer?->force_number ?? 'N/A' }}</dd>
                <dt class="font-semibold text-gray-600 dark:text-gray-400">Phone Number:</dt>
                <dd class="text-gray-900 dark:text-gray-100">{{ $case->photographer?->phone_number ?? 'N/A' }}</dd>
            </dl>
        </div>

        {{-- Conditionally rendered cards for each person type --}}
        @if ($case->informant)
        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md">
            <h3 class="text-xl font-semibold mb-4 border-b dark:border-gray-700 pb-2 text-gray-800 dark:text-gray-200">Informant Details</h3>
            <dl class="grid grid-cols-[auto,1fr] gap-x-6 gap-y-3 text-base">
                <dt class="font-semibold text-gray-600 dark:text-gray-400">Full Name:</dt><dd class="text-gray-900 dark:text-gray-100">{{ $case->informant->fullName }}</dd>
                <dt class="font-semibold text-gray-600 dark:text-gray-400">ID Number:</dt><dd class="text-gray-900 dark:text-gray-100">{{ $case->informant->id_number ?? 'N/A' }}</dd>
                <dt class="font-semibold text-gray-600 dark:text-gray-400">Address:</dt><dd class="text-gray-900 dark:text-gray-100">{{ $case->informant->address ?? 'N/A' }}</dd>
                <dt class="font-semibold text-gray-600 dark:text-gray-400">Phone:</dt><dd class="text-gray-900 dark:text-gray-100">{{ $case->informant->phone_number ?? 'N/A' }}</dd>
            </dl>
        </div>
        @endif
        {{-- ... (Repeat for deceased, complainant, and accused) ... --}}

        <div class="lg:col-span-2 bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md">
            <h3 class="text-xl font-semibold mb-4 border-b dark:border-gray-700 pb-2 text-gray-800 dark:text-gray-200">Circumstances</h3>
            <p class="text-gray-800 dark:text-gray-200 whitespace-pre-line">{{ $case->circumstances }}</p>
        </div>
    </div> <!-- End of grid -->

    <!-- Action Bar -->
    <div class="mt-8 pt-6 border-t dark:border-gray-700 flex flex-wrap items-center gap-4">
        @can('reassign', $case)
        <a href="{{ route('admin.cases.edit', $case->id) }}" class="bg-yellow-500 text-white px-4 py-2 rounded-md hover:bg-yellow-600 font-semibold flex items-center gap-2">
            <i data-feather="user-check" class="w-5 h-5"></i> Reassign Photographer
        </a>
        @endcan
        
        <button type="button" @click="isExportModalOpen = true" class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700 font-semibold flex items-center gap-2">
            <i data-feather="file-text" class="w-5 h-5"></i> Export PDF Exhibit
        </button>

        <form action="{{ route('admin.cases.destroy', $case->id) }}" method="POST" onsubmit="deleteConfirmation(event, this)">
            @csrf
            @method('DELETE')
            <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded-md hover:bg-red-600 font-semibold flex items-center gap-2">
                <i data-feather="trash-2" class="w-5 h-5"></i> Delete Case
            </button>
        </form>
    </div>

    <!-- Media Gallery -->
    <div class="mt-8 bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md">
        <h3 class="text-xl font-semibold mb-6 border-b dark:border-gray-700 pb-2 text-gray-800 dark:text-gray-200">Attached Media</h3>
        @if($case->media->isEmpty())
            <p class="text-gray-500 dark:text-gray-400 italic">No media files have been uploaded for this case yet.</p>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @foreach($case->media as $media)
                    <div class="border dark:border-gray-700 rounded-lg p-3 shadow-sm text-center bg-gray-50 dark:bg-gray-700/50">
                        @if($media->type === 'image')
                            <a href="{{ asset('storage/' . $media->path) }}" target="_blank"><img src="{{ asset('storage/' . $media->path) }}" alt="Case Image" class="w-full h-40 object-cover rounded-md mb-2 hover:opacity-80 transition"></a>
                        @elseif($media->type === 'video')
                            <video controls class="w-full h-40 rounded-md mb-2"><source src="{{ asset('storage/' . $media->path) }}" type="video/mp4">Your browser does not support the video tag.</video>
                        @endif
                        <p class="text-sm text-gray-600 dark:text-gray-300 truncate" title="{{ $media->description ?? 'No description' }}">{{ $media->description ?? 'No description' }}</p>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
    
    <!-- EXPORT PDF MODAL -->
    <div x-show="isExportModalOpen" x-cloak class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4 transition-opacity duration-300" x-transition:enter="ease-out" x-transition:leave="ease-in">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl p-6 w-full max-w-2xl" @click.away="isExportModalOpen = false">
            <h3 class="text-xl font-bold mb-4 text-gray-800 dark:text-gray-200">Select Media for Exhibit</h3>
            
            <form action="{{ route('admin.cases.exportPdf', $case->id) }}" method="POST" target="_blank">
                @csrf
                @if($case->media->isEmpty())
                    <p class="text-gray-500 dark:text-gray-400">There is no media to select for this case.</p>
                @else
                    <div class="max-h-96 overflow-y-auto border dark:border-gray-700 rounded-md p-4 space-y-4">
                        @foreach($case->media as $media)
                            <label class="flex items-center gap-4 p-2 rounded hover:bg-gray-100 dark:hover:bg-gray-700 cursor-pointer">
                                <input type="checkbox" name="media_ids[]" value="{{ $media->id }}" checked class="h-5 w-5 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                @if($media->type === 'image')
                                <img src="{{ asset('storage/' . $media->path) }}" alt="Thumbnail" class="w-20 h-16 object-cover rounded">
                                @else
                                <div class="w-20 h-16 bg-gray-200 dark:bg-gray-700 rounded flex items-center justify-center">
                                    <i data-feather="video" class="w-8 h-8 text-gray-500"></i>
                                </div>
                                @endif
                                <span class="text-gray-700 dark:text-gray-300">{{ $media->description ?? 'No description' }}</span>
                            </label>
                        @endforeach
                    </div>
                @endif
                
                <div class="mt-6 flex justify-end gap-4">
                    <button type="button" @click="isExportModalOpen = false" class="bg-gray-200 dark:bg-gray-600 text-gray-700 dark:text-gray-300 px-4 py-2 rounded-md hover:bg-gray-300 dark:hover:bg-gray-500 font-semibold">Cancel</button>
                    <button type="submit" @if($case->media->isEmpty()) disabled @endif class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700 font-semibold disabled:bg-gray-400 disabled:cursor-not-allowed">
                        Generate Exhibit
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
{{-- This script handles the SweetAlert confirmation for the delete action --}}
<script>
    function deleteConfirmation(event, form) {
        event.preventDefault();
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this! Deleting a case will also delete all associated media.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        })
    }
</script>
@endpush