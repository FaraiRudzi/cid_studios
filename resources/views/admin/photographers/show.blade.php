@extends('layouts.app')

@section('title', 'Photographer Details')

@section('content')
<div class="container mx-auto">

    {{-- Header: Page Title and "Back to List" Link (aligned with card) --}}
    <div class="max-w-4xl mx-auto mb-6 flex justify-between items-center">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-200">
            Photographer Details
        </h1>
        
        <a href="{{ route('admin.photographers.index') }}" class="text-blue-600 dark:text-blue-400 hover:underline flex items-center gap-2">
            <i data-feather="arrow-left" class="w-5 h-5"></i>
            Back to List
        </a>
    </div>

    {{-- Main Content Card with a reasonable max-width --}}
    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md max-w-4xl mx-auto">
        
        {{-- Description List styled as a two-column grid for single-line display --}}
        <dl class="grid grid-cols-[auto,1fr] gap-x-6 gap-y-3 text-base">
            
            <dt class="font-semibold text-gray-600 dark:text-gray-400">Full Name:</dt>
            <dd class="text-gray-900 dark:text-gray-100">{{ $photographer->first_name }} {{ $photographer->surname }}</dd>
            
            <dt class="font-semibold text-gray-600 dark:text-gray-400">Force Number:</dt>
            <dd class="text-gray-900 dark:text-gray-100">{{ $photographer->force_number }}</dd>
            
            <dt class="font-semibold text-gray-600 dark:text-gray-400">Username:</dt>
            <dd class="text-gray-900 dark:text-gray-100">{{ $photographer->username }}</dd>
            
            <dt class="font-semibold text-gray-600 dark:text-gray-400">Email Address:</dt>
            <dd class="text-gray-900 dark:text-gray-100">{{ $photographer->email }}</dd>
            
            <dt class="font-semibold text-gray-600 dark:text-gray-400">Phone Number:</dt>
            <dd class="text-gray-900 dark:text-gray-100">{{ $photographer->phone_number }}</dd>
            
            <dt class="font-semibold text-gray-600 dark:text-gray-400">Joined On:</dt>
            <dd class="text-gray-900 dark:text-gray-100">
                {{ $photographer->created_at ? $photographer->created_at->format('d M Y, H:i A') : 'N/A' }}
            </dd>
            
        </dl>
        
        <hr class="my-6 border-gray-200 dark:border-gray-700">

        {{-- Action Buttons --}}
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.photographers.edit', $photographer->id) }}" class="bg-yellow-500 text-white px-4 py-2 rounded-md hover:bg-yellow-600 font-semibold flex items-center gap-2">
                <i data-feather="edit" class="w-5 h-5"></i>
                Edit Photographer
            </a>
            
            <form method="POST" action="{{ route('admin.photographers.destroy', $photographer->id) }}" onsubmit="deleteConfirmation(event, this)">
                @csrf
                @method('DELETE')
                <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded-md hover:bg-red-600 font-semibold flex items-center gap-2">
                    <i data-feather="trash-2" class="w-5 h-5"></i>
                    Delete Photographer
                </button>
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
            text: "You won't be able to revert this!",
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