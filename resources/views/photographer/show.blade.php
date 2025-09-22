@extends('layouts.app')

@section('title', 'Case Details')

@section('content')
<div class="max-w-6xl mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold text-gray-800 mb-6">Case: {{ $case->reference_number }}</h1>

    <!-- Case Details -->
    <div class="grid sm:grid-cols-2 gap-6 bg-white p-6 rounded-lg shadow-md mb-8">
        <div>
            <p class="text-gray-700"><span class="font-semibold">Station:</span> {{ $case->station->name ?? 'N/A' }}</p>
            <p class="text-gray-700"><span class="font-semibold">Type:</span> {{ $case->case_type }}</p>
            <p class="text-gray-700"><span class="font-semibold">Circumstances:</span> {{ $case->circumstances }}</p>
        </div>
        <div>
            <p class="text-gray-700"><span class="font-semibold">Photographer:</span> {{ $case->photographer->first_name }} {{ $case->photographer->surname }}</p>
            <p class="text-gray-700"><span class="font-semibold">Cause of Death:</span> {{ $case->cause_of_death ?? 'Not yet provided' }}</p>
        </div>
    </div>

    <!-- Cause of Death Update -->
    @if(in_array($case->case_type, ['Murder', 'Sudden Death']))
        <div class="bg-white p-6 rounded-lg shadow-md mb-8">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Update Cause of Death</h2>
            <form action="{{ route('photographer.cause.update', $case->id) }}" method="POST" class="space-y-4">
                @csrf
                @method('PUT')
                <input type="text" name="cause_of_death" value="{{ old('cause_of_death', $case->cause_of_death) }}"
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="e.g. Blunt force trauma" required>
                <button type="submit"
                    class="bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700 transition duration-200">
                    Save
                </button>
            </form>
        </div>
    @endif

    <!-- Upload Media -->
    <div class="bg-white p-6 rounded-lg shadow-md mb-8">
        <h2 class="text-xl font-semibold text-gray-800 mb-4">Upload Media</h2>
        <form action="{{ route('photographer.media.store', $case->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            <div>
                <label for="media" class="block font-medium text-gray-700 mb-1">Media File</label>
                <input type="file" name="media" id="media"
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            </div>
            <div>
                <label for="type" class="block font-medium text-gray-700 mb-1">Media Type</label>
                <select name="type" id="type"
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    <option value="image">Image</option>
                    <option value="video">Video</option>
                </select>
            </div>
            <div>
                <label for="description" class="block font-medium text-gray-700 mb-1">Description</label>
                <input type="text" name="description" id="description"
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="e.g. Body lying in pool of blood" required>
            </div>
            <button type="submit"
                class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition duration-200">
                Upload
            </button>
        </form>
    </div>

    <!-- Media Gallery -->
    <div class="bg-white p-6 rounded-lg shadow-md">
        <h2 class="text-xl font-semibold text-gray-800 mb-4">Uploaded Media</h2>

        @if($case->media->isEmpty())
            <p class="text-gray-500">No media has been uploaded for this case.</p>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                @foreach($case->media as $media)
                    <div class="border rounded-lg shadow-sm overflow-hidden">
                        <div class="p-2">
                            @if($media->type === 'image')
                                <img src="{{ asset('storage/' . $media->path) }}" alt="Case Image"
                                     class="w-full h-48 object-cover rounded mb-2" />
                            @elseif($media->type === 'video')
                                <video controls class="w-full h-48 rounded mb-2">
                                    <source src="{{ asset('storage/' . $media->path) }}" type="video/mp4">
                                </video>
                            @endif

                            <p class="text-sm text-gray-600 italic mb-3">{{ $media->description }}</p>

                            <div class="flex justify-between items-center text-sm">
                                <a href="{{ route('photographer.media.edit', $media->id) }}"
                                   class="text-blue-600 hover:underline">Edit</a>

                                <form action="{{ route('photographer.media.destroy', $media->id) }}" method="POST"
                                      onsubmit="return confirm('Are you sure you want to delete this media?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:underline">Delete</button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection
