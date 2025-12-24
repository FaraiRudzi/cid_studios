@extends('layouts.app')

@section('title', 'Manage Cases')

@section('content')
<div class="max-w-7xl mx-auto pb-12 px-4 font-sans antialiased">

    {{-- Header: Identity --}}
    <div class="mb-8 border-b-2 border-gray-200 pb-6 flex justify-between items-end">
        <a href="{{ route('admin.cases.create') }}" class="flex items-center gap-2 px-4 py-2 bg-zrp-blue text-white text-base font-bold uppercase italic rounded-lg hover:bg-zrp-gold transition-all">
            <i data-feather="plus-circle" class="w-5 h-5"></i>
            Add New Case
        </a>
    </div>

    {{-- Action Bar: Search & Filters --}}
    <div class="mb-8 flex flex-col lg:flex-row items-center justify-between gap-6">

        {{-- Search Form --}}
        <form method="GET" action="{{ route('admin.cases.index') }}" class="flex items-center gap-0 w-full lg:w-1/2">
            <input type="hidden" name="per_page" value="{{ request('per_page', 10) }}">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by reference or station..."
                   class="w-full border-2 border-zrp-blue/20 bg-white px-4 py-2 text-base font-bold text-zrp-blue  outline-none focus:border-zrp-blue rounded-l-lg">
            <button type="submit" class="bg-zrp-blue text-white p-3 rounded-r-lg hover:bg-black transition-all">
                <i data-feather="search" class="w-5 h-5"></i>
            </button>
            @if(request('search'))
                <a href="{{ route('admin.cases.index') }}" class="ml-2 text-zrp-blue hover:bg-zrp-blue hover:text-white p-2 rounded transition-all">
                    <i data-feather="refresh-cw" class="w-5 h-5"></i>
                </a>
            @endif
        </form>

        {{-- Per Page Selector --}}
        <form method="GET" action="{{ route('admin.cases.index') }}" class="flex items-center gap-3 text-base font-bold text-zrp-blue">
            <input type="hidden" name="search" value="{{ request('search') }}">
            <label for="per_page">Entries per page:</label>
            <select name="per_page" id="per_page" onchange="this.form.submit()"
                    class="border-2 border-zrp-blue/20 bg-white rounded-lg px-2 py-1 text-base font-bold outline-none focus:border-zrp-blue">
                <option value="10" @selected(request('per_page', 10) == 10)>10</option>
                <option value="25" @selected(request('per_page') == 25)>25</option>
                <option value="50" @selected(request('per_page') == 50)>50</option>
            </select>
        </form>
    </div>

    {{-- Table Section --}}
    <div class="overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="border-b-2 border-zrp-blue/20 text-zrp-blue uppercase text-base font-black">
                    <th class="py-4 px-2 w-16">#</th>
                    <th class="py-4 px-2">Case Reference</th>
                    <th class="py-4 px-2">Station</th>
                    <th class="py-4 px-2">Photographer</th>
                    <th class="py-4 px-2 text-right">View</th>
                </tr>
            </thead>
            <tbody class="text-zrp-blue uppercase text-base font-bold italic divide-y divide-gray-100">
                @forelse($cases as $case)
                <tr class="hover:bg-zrp-blue/5 transition-all group">
                    <td class="py-4 px-2 not-italic font-black opacity-50">
                        {{ ($cases->currentPage() - 1) * $cases->perPage() + $loop->iteration }}
                    </td>
                    <td class="py-4 px-2">
                        {{ $case->scene_reference_number }}
                    </td>
                    <td class="py-4 px-2">
                        {{ $case->station->name ?? 'N/A' }}
                    </td>
                    <td class="py-4 px-2">
                        {{ $case->photographer->surname ?? 'UNASSIGNED' }}
                    </td>
                    <td class="py-4 px-2 text-right">
                        <a href="{{ route('admin.cases.show', $case) }}"
                           class="inline-flex items-center justify-center p-2 rounded-lg hover:bg-zrp-blue hover:text-white transition-all">
                            <i data-feather="eye" class="w-5 h-5"></i>
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="py-20 text-center">
                        <i data-feather="folder-minus" class="w-12 h-12 text-zrp-blue/20 mx-auto mb-4"></i>
                        <p class="text-base italic font-bold text-zrp-blue uppercase">No records found matching criteria.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

        {{-- Pagination --}}
        @if ($cases->hasPages())
        <div class="mt-8 pt-6 border-t border-gray-100">
            {{ $cases->appends(request()->query())->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
