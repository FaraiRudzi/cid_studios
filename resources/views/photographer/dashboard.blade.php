@extends('layouts.photographer')
@section('title', 'My Assigned Cases')

@section('content')
<div class="max-w-6xl mx-auto">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-8">
        <div>
            <h2 class="text-3xl font-black text-zrp-blue dark:text-zrp-gold tracking-tight uppercase italic">Assigned Investigations</h2>
            <p class="text-gray-500 dark:text-gray-400 font-medium">Manage and upload scene evidence for your active cases.</p>
        </div>

        <div class="bg-white dark:bg-gray-800 p-2 rounded-2xl shadow-sm border dark:border-gray-700 flex items-center gap-2">
            <form action="{{ route('photographer.dashboard') }}" method="GET" class="flex items-center gap-2">
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Search Reference..."
                       class="bg-transparent border-none focus:ring-0 text-sm font-bold px-4 py-2 w-48 md:w-64">
                <button type="submit" class="p-2 bg-zrp-blue text-zrp-gold rounded-xl hover:scale-105 transition-transform">
                    <i data-feather="search" class="w-5 h-5"></i>
                </button>
            </form>
            @if(request('search'))
                <a href="{{ route('photographer.dashboard') }}" class="p-2 text-gray-400 hover:text-red-500">
                    <i data-feather="x-circle" class="w-5 h-5"></i>
                </a>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
        <div class="bg-white dark:bg-gray-800 p-6 rounded-3xl shadow-sm border-l-4 border-zrp-blue">
            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Active Assignments</p>
            <h4 class="text-3xl font-black text-zrp-blue dark:text-white mt-1">{{ $cases->total() }}</h4>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-[2rem] shadow-xl shadow-zrp-blue/5 overflow-hidden border dark:border-gray-700">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-gray-50 dark:bg-gray-900/50 border-b dark:border-gray-700">
                        <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest">Investigation Reference</th>
                        <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest">Case Type</th>
                        <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">Forensic Status</th>
                        <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y dark:divide-gray-700">
                    @forelse($cases as $case)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/40 transition-colors group">
                        <td class="px-8 py-6">
                            <span class="block font-black text-zrp-blue dark:text-white text-lg">#{{ $case->scene_reference_number ?? $case->reference_number }}</span>
                            <span class="text-xs font-bold text-gray-400 uppercase tracking-tight">{{ optional($case->station)->name }}</span>
                        </td>
                        <td class="px-8 py-6">
                            <div class="flex items-center gap-2">
                                @if($case->case_type == 'Murder')
                                    <span class="flex h-2 w-2 rounded-full bg-red-500"></span>
                                @else
                                    <span class="flex h-2 w-2 rounded-full bg-zrp-blue"></span>
                                @endif
                                <span class="text-sm font-bold uppercase italic">{{ $case->case_type }}</span>
                            </div>
                        </td>
                        <td class="px-8 py-6 text-center">
                            @if($case->cause_of_death)
                                <span class="px-4 py-1.5 rounded-full text-[10px] font-black bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400 uppercase">
                                    Finalised: {{ Str::limit($case->cause_of_death, 15) }}
                                </span>
                            @else
                                <span class="px-4 py-1.5 rounded-full text-[10px] font-black bg-zrp-gold/20 text-zrp-gold-hover dark:bg-zrp-gold/10 dark:text-zrp-gold uppercase">
                                    Evidence Pending
                                </span>
                            @endif
                        </td>
                        <td class="px-8 py-6 text-right">
                            {{-- Updated Route Name: photographer.case.show --}}
                            <a href="{{ route('photographer.case.show', $case) }}"
                               class="inline-flex items-center gap-2 px-6 py-2.5 bg-zrp-blue text-zrp-gold rounded-xl font-black text-[10px] uppercase tracking-widest hover:bg-zrp-gold hover:text-zrp-blue transition-all group-hover:shadow-lg">
                                <i data-feather="camera" class="w-4 h-4"></i>
                                Enter Scene
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-8 py-20 text-center">
                            <div class="flex flex-col items-center">
                                <div class="h-20 w-20 bg-gray-50 dark:bg-gray-900 rounded-full flex items-center justify-center mb-4">
                                    <i data-feather="folder-minus" class="w-10 h-10 text-gray-300"></i>
                                </div>
                                <h3 class="text-xl font-black text-zrp-blue dark:text-white">No Assigned Cases</h3>
                                <p class="text-gray-400 text-sm mt-1">You currently have no investigations assigned to your profile.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($cases->hasPages())
        <div class="p-6 bg-gray-50 dark:bg-gray-900/50 border-t dark:border-gray-700">
            {{ $cases->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
