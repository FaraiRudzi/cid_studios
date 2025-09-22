@extends('layouts.app')

@section('title', 'Manage Stations')

@section('content')
<div class="container mx-auto">

    {{-- Header: Page Title (aligned with the content below) --}}
    <div class="max-w-4xl mx-auto mb-4">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-200">
            Manage Stations
        </h1>
    </div>
    
    <!-- Action Bar: Contains Add New, Per Page, and Filter -->
    <div class="max-w-4xl mx-auto mb-6 bg-white dark:bg-gray-800 p-4 rounded-lg shadow-md flex flex-col sm:flex-row items-center justify-between gap-4">
        
        <!-- Add New Button (on the left) -->
        <a href="{{ route('admin.stations.create') }}" class="w-full sm:w-auto bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 font-semibold flex items-center justify-center gap-2">
            <i data-feather="plus-circle" class="w-5 h-5"></i>
            Add New Station
        </a>

        <!-- Filter Controls (on the right) -->
        <div class="flex flex-col sm:flex-row items-center gap-4 w-full sm:w-auto">
            
            <!-- Per Page Selector -->
            <form method="GET" action="{{ route('admin.stations.index') }}" class="flex items-center gap-2">
                <input type="hidden" name="search" value="{{ request('search') }}">
                <label for="per_page" class="text-sm font-medium text-gray-600 dark:text-gray-400">Show:</label>
                <select name="per_page" id="per_page" class="border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300 rounded-md shadow-sm text-sm" onchange="this.form.submit()">
                    <option value="10" @selected(request('per_page', 10) == 10)>10</option>
                    <option value="25" @selected(request('per_page') == 25)>25</option>
                    <option value="50" @selected(request('per_page') == 50)>50</option>
                </select>
            </form>

            <!-- Search Form -->
            <form method="GET" action="{{ route('admin.stations.index') }}" class="flex items-center gap-2 w-full sm:w-auto">
                <input type="hidden" name="per_page" value="{{ request('per_page', 10) }}">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search stations..." class="w-full sm:w-56 border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300 rounded-md shadow-sm text-sm">
                
                <button type="submit" class="bg-blue-600 text-white p-2 rounded-md hover:bg-blue-700" title="Search">
                    <i data-feather="search" class="w-5 h-5"></i>
                </button>
                <a href="{{ route('admin.stations.index') }}" class="bg-gray-200 dark:bg-gray-600 text-gray-700 dark:text-gray-300 p-2 rounded-md hover:bg-gray-300 dark:hover:bg-gray-500" title="Reset Filter">
                    <i data-feather="refresh-cw" class="w-5 h-5"></i>
                </a>
            </form>
        </div>
    </div>

    {{-- Main Content Card with a reasonable max-width for the table --}}
    {{-- Main Content Card with a reasonable max-width for the table --}}
<div class="bg-white dark:bg-gray-800 p-4 sm:p-6 rounded-lg shadow-md max-w-4xl mx-auto">

    <div class="overflow-x-auto max-h-[500px] overflow-y-auto"> {{-- Add vertical scroll with max height --}}
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead class="bg-gray-50 dark:bg-gray-700 sticky top-0 z-10"> {{-- Sticky header --}}
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider w-16">#</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Station Name</th>
                    <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider w-32">Action</th>
                </tr>
            </thead>
            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                @forelse($stations as $station)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $loop->iteration }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">{{ $station->name }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                        <a href="{{ route('admin.stations.show', $station) }}" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300 font-semibold flex items-center justify-center gap-1" title="View Details">
                            <i data-feather="eye" class="w-4 h-4"></i>
                            View
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" class="text-center py-10 text-gray-500 dark:text-gray-400">
                        <div class="flex flex-col items-center">
                            <i data-feather="map-pin" class="w-12 h-12 text-gray-300 dark:text-gray-600"></i>
                            <h3 class="mt-2 text-lg font-medium">No Stations Found</h3>
                            <p class="mt-1 text-sm text-gray-400 dark:text-gray-500">Your search returned no results, or no stations have been added yet.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination Links (preserving all query parameters) --}}
    <div class="mt-6">
        {{ $stations->appends(request()->query())->links() }}
    </div>
</div>

</div>
@endsection