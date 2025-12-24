

@extends('layouts.app')
@section('title', 'Edit Station')

@section('content')
<h1 class="text-2xl font-bold mb-4">Edit Station</h1>
<form method="POST" action="{{ route('admin.stations.update', $station->id) }}" class="bg-white p-6 rounded shadow max-w-md">
    @csrf
    @method('PUT')
    <div class="mb-4">
        <label for="name" class="block font-medium">Station Name</label>
        <input type="text" name="name" id="name" value="{{ $station->name }}" class="w-full border p-2 rounded" required>
        @error('name')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
        @enderror
    </div>
    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Update</button>
    <a href="{{ route('admin.stations.index') }}" class="ml-4 text-gray-600 hover:underline">Cancel</a>
</form>
@endsection