@extends('layouts.app')

@section('title', 'Edit Photographer')

@section('content')
<div class="container mx-auto">

    {{-- Header: Page Title and "Back" Link --}}
    <div class="flex justify-between items-center mb-6 max-w-4xl mx-auto">
        <h1 class="text-2xl font-bold text-gray-800">
            Edit Photographer
        </h1>
        
        <a href="{{ route('admin.photographers.show', $photographer->id) }}" class="text-blue-600 hover:underline flex items-center gap-2">
            <i data-feather="arrow-left" class="w-5 h-5"></i>
            Back to Photographer Details
        </a>
    </div>

    {{-- Main Form Card with a max-width for better readability --}}
    <div class="bg-white p-6 rounded-lg shadow-md max-w-4xl mx-auto">
        <form action="{{ route('admin.photographers.update', $photographer->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            {{-- Section 1: Personal & Contact Info --}}
            <h3 class="text-lg font-semibold text-gray-800 border-b pb-2">Personal & Contact Information</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="first_name" class="block font-semibold text-gray-700 mb-1">First Name</label>
                    <input type="text" name="first_name" id="first_name" value="{{ old('first_name', $photographer->first_name) }}" required class="w-full border-gray-300 rounded-md shadow-sm">
                </div>
                <div>
                    <label for="surname" class="block font-semibold text-gray-700 mb-1">Surname</label>
                    <input type="text" name="surname" id="surname" value="{{ old('surname', $photographer->surname) }}" required class="w-full border-gray-300 rounded-md shadow-sm">
                </div>
                 <div>
                    <label for="phone_number" class="block font-semibold text-gray-700 mb-1">Phone Number</label>
                    <input type="text" name="phone_number" id="phone_number" value="{{ old('phone_number', $photographer->phone_number) }}" required class="w-full border-gray-300 rounded-md shadow-sm">
                </div>
                <div>
                    <label for="email" class="block font-semibold text-gray-700 mb-1">Email Address</label>
                    <input type="email" name="email" id="email" value="{{ old('email', $photographer->email) }}" required class="w-full border-gray-300 rounded-md shadow-sm">
                </div>
            </div>

            {{-- Section 2: Account Credentials --}}
            <h3 class="text-lg font-semibold text-gray-800 border-b pb-2 pt-4">Account Credentials</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="force_number" class="block font-semibold text-gray-700 mb-1">Force Number</label>
                    <input type="text" name="force_number" id="force_number" value="{{ old('force_number', $photographer->force_number) }}" required class="w-full border-gray-300 rounded-md shadow-sm">
                </div>
                <div>
                    <label for="username" class="block font-semibold text-gray-700 mb-1">Username</label>
                    <input type="text" name="username" id="username" value="{{ old('username', $photographer->username) }}" required class="w-full border-gray-300 rounded-md shadow-sm">
                </div>
                <div>
                    <label for="password" class="block font-semibold text-gray-700 mb-1">New Password</label>
                    <input type="password" name="password" id="password" placeholder="Leave blank to keep current" class="w-full border-gray-300 rounded-md shadow-sm">
                </div>
                <div>
                    <label for="password_confirmation" class="block font-semibold text-gray-700 mb-1">Confirm New Password</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" class="w-full border-gray-300 rounded-md shadow-sm">
                </div>
            </div>

            {{-- Form Actions --}}
            <div class="pt-6 flex items-center gap-4 border-t mt-6">
                <button type="submit" class="bg-blue-600 text-white px-5 py-2 rounded-md hover:bg-blue-700 font-semibold flex items-center gap-2">
                    <i data-feather="save" class="w-5 h-5"></i>
                    Update Photographer
                </button>
                <a href="{{ route('admin.photographers.show', $photographer->id) }}" class="text-gray-600 hover:underline">Cancel</a>
            </div>
        </form>
    </div>
</div>

{{-- This script will display validation errors in a SweetAlert pop-up --}}
@if ($errors->any())
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                icon: 'error',
                title: 'Validation Error',
                html: `{!! implode('<br>', $errors->all()) !!}`
            });
        });
    </script>
@endif

@endsection