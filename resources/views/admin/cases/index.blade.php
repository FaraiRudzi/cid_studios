@extends('layouts.app')

@section('title', 'Manage Cases')

@section('content')
<div class="container mx-auto">

    {{-- Header: Page Title --}}
    <div class="mb-4">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-200">
            Manage Cases
        </h1>
    </div>
    
    <!-- Action Bar: Contains Add New, Per Page, and Filter -->
    <div class="mb-6 bg-white dark:bg-gray-800 p-4 rounded-lg shadow-md flex flex-col sm:flex-row items-center justify-between gap-4">
        
        <a href="{{ route('admin.cases.create') }}" class="w-full sm:w-auto bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 font-semibold flex items-center justify-center gap-2">
            <i data-feather="plus-circle" class="w-5 h-5"></i>
            Add New Case
        </a>

        <div class="flex flex-col sm:flex-row items-center gap-4 w-full sm:w-auto">
            <form method="GET" action="{{ route('admin.cases.index') }}" class="flex items-center gap-2">
                <input type="hidden" name="search" value="{{ request('search') }}">
                <label for="per_page" class="text-sm font-medium text-gray-600 dark:text-gray-400">Show:</label>
                <select name="per_page" id="per_page" class="border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300 rounded-md shadow-sm text-sm" onchange="this.form.submit()">
                    <option value="10" @selected(request('per_page', 10) == 10)>10</option>
                    <option value="25" @selected(request('per_page') == 25)>25</option>
                    <option value="50" @selected(request('per_page') == 50)>50</option>
                </select>
            </form>
            <form method="GET" action="{{ route('admin.cases.index') }}" class="flex items-center gap-2 w-full sm:w-auto">
                <input type="hidden" name="per_page" value="{{ request('per_page', 10) }}">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search cases..." class="w-full sm:w-56 border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300 rounded-md shadow-sm text-sm">
                <button type="submit" class="bg-blue-600 text-white p-2 rounded-md hover:bg-blue-700" title="Search"><i data-feather="search" class="w-5 h-5"></i></button>
                <a href="{{ route('admin.cases.index') }}" class="bg-gray-200 dark:bg-gray-600 text-gray-700 dark:text-gray-300 p-2 rounded-md hover:bg-gray-300 dark:hover:bg-gray-500" title="Reset Filter"><i data-feather="refresh-cw" class="w-5 h-5"></i></a>
            </form>
        </div>
    </div>

    {{-- Main Content - Clean Minimalist Table --}}
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden">
        
        <table class="min-w-full">
            <thead class="bg-gray-50 dark:bg-gray-700/50">
                <tr>
                    {{-- The header for the index number column --}}
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider w-16">#</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                        Case Details
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                        Assigned To
                    </th>
                    <th scope="col" class="px-6 py-3 text-right text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                        Action
                    </th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                @forelse($cases as $case)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors duration-200">
                    {{-- THE FIX: This cell correctly calculates the overall index number --}}
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                        {{ ($cases->currentPage() - 1) * $cases->perPage() + $loop->iteration }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $case->scene_reference_number }}</div>
                        <div class="text-sm text-gray-500 dark:text-gray-400">{{ $case->station->name ?? 'N/A' }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-300">
                        {{ $case->photographer->first_name ?? 'N/A' }} {{ $case->photographer->surname ?? '' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                        <a href="{{ route('admin.cases.show', $case) }}" class="text-indigo-600 dark:text-indigo-400 hover:underline font-semibold inline-flex items-center gap-1">
                            <i data-feather="eye" class="w-4 h-4"></i>
                            View
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center py-10">
                        <div class="flex flex-col items-center">
                            <i data-feather="folder" class="w-12 h-12 text-gray-300 dark:text-gray-600"></i>
                            <h3 class="mt-2 text-lg font-medium text-gray-900 dark:text-gray-200">No Cases Found</h3>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Your search returned no results.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
        
        @if ($cases->hasPages())
        <div class="p-4 bg-gray-50 dark:bg-gray-700/50 border-t dark:border-gray-700">
            {{ $cases->appends(request()->query())->links() }}
        </div>
        @endif
    </div>
</div>
@endsection