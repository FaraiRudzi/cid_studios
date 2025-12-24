@extends('layouts.app')

@section('title', 'Edit Media')

@section('content')
    <div class="max-w-md mx-auto bg-white p-6 rounded shadow">
        <h2 class="text-lg font-semibold mb-4">Edit Media Description</h2>

        <form action="{{ route('photographer.media.update', $media->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label for="description" class="block font-semibold mb-1">Description</label>
                <input type="text" name="description" value="{{ old('description', $media->description) }}"
                       class="w-full border px-3 py-2 rounded" required />
            </div>

            <div class="flex justify-between">
                <a href="{{ route('photographer.case.show', $media->case_id) }}" class="text-gray-600 hover:underline">← Back to Case</a>
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                    Update
                </button>
            </div>
        </form>
    </div>
@endsection
