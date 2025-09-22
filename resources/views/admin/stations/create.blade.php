@extends('layouts.app')

@section('title', 'Add New Station')

@section('content')
<div class="container mx-auto">

    {{-- Header: Page Title and "Back" Link --}}
    <div class="max-w-xl mx-auto mb-6 flex justify-between items-center">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-200">
            Add New Station
        </h1>
        
        <a href="{{ route('admin.stations.index') }}" class="text-blue-600 dark:text-blue-400 hover:underline flex items-center gap-2">
            <i data-feather="arrow-left" class="w-5 h-5"></i>
            Back to Station List
        </a>
    </div>

    {{-- Main Form Card with a reasonable max-width --}}
    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md max-w-xl mx-auto">
        <form action="{{ route('admin.stations.store') }}" method="POST">
            @csrf

            {{-- Form Field --}}
            <div>
                <label for="name" class="block font-semibold text-gray-700 dark:text-gray-300 mb-1">Station Name</label>
                <input type="text" 
                       name="name" 
                       id="name" 
                       value="{{ old('name') }}" 
                       class="w-full border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300 rounded-md shadow-sm" 
                       required 
                       autofocus>
            </div>

            {{-- Form Actions --}}
            <div class="pt-6 flex items-center gap-4 border-t dark:border-gray-700 mt-6">
                <button type="submit" class="bg-blue-600 text-white px-5 py-2 rounded-md hover:bg-blue-700 font-semibold flex items-center gap-2">
                    <i data-feather="save" class="w-5 h-5"></i>
                    Save Station
                </button>
                <a href="{{ route('admin.stations.index') }}" class="text-gray-600 dark:text-gray-400 hover:underline">Cancel</a>
            </div>
        </form>
    </div>
</div>

{{-- This script will display validation errors in a SweetAlert pop-up --}}
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        @if ($errors->any())
            Swal.fire({
                icon: 'error',
                title: 'Validation Error',
                html: `{!! $errors->first('name') !!}` // Show only the first error for a clean message
            });
        @endif
    });
</script>
@endpush

@endsection