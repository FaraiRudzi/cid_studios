@extends('layouts.app')

@section('title', $case->scene_reference_number . ' Details')

@section('content')
<div class="max-w-7xl mx-auto pb-12 px-4 font-sans antialiased">

    {{-- NAVIGATION --}}
    <div class="flex mb-8 border-b-2 border-gray-200 pb-6">
        <a href="{{ route('admin.cases.index') }}"
           class="inline-flex items-center gap-2 px-5 py-2.5 bg-white dark:bg-gray-800 text-zrp-blue dark:text-gray-300 border-2 border-gray-100 dark:border-gray-700 rounded-xl font-bold text-sm hover:border-blue-500 transition-all shadow-sm">
            <i data-feather="arrow-left" class="w-4 h-4"></i>
            Back to Case List
        </a>
    </div>

    {{-- HEADER SECTION: 50/50 Split --}}
    <div class="flex flex-col md:flex-row gap-8 mb-12 border-b-2 border-gray-200 pb-8">
        {{-- Left Side: Identity & Classification --}}
        <div class="md:w-1/2 space-y-4">
            <div class="inline-block px-4 py-1 bg-zrp-blue text-white text-xs font-black uppercase tracking-widest rounded-full mb-2">
                Classification: {{ $case->case_type }}
            </div>

            <div class="text-zrp-blue uppercase text-base font-bold flex flex-col gap-1">
                <div class="italic text-2xl font-black mb-2">{{ $case->scene_reference_number }}</div>
                <div class="italic">Station: {{ $case->station->name }} ({{ $case->reference_number }})</div>
                <div class="italic">Date Opened: {{ $case->created_at->format('d/m/Y H:i') }}</div>

                @if($case->cause_of_death)
                    <div class="mt-4 p-3 bg-red-50 border-l-4 border-red-500 text-red-700">
                        <span class="font-black">CAUSE OF DEATH:</span> {{ strtoupper($case->cause_of_death) }}
                    </div>
                @endif

                <div class="mt-6 pt-4 border-t border-gray-100">
                    <h4 class="text-xs font-black text-gray-400 uppercase tracking-widest">Lead Photographer</h4>
                    <div class="italic text-base">{{ $case->photographer->force_number }} {{ $case->photographer->surname }} | {{ $case->photographer->phone_number }}</div>
                </div>
            </div>
        </div>

        {{-- Right Side: Brief Circumstances --}}
        <div class="md:w-1/2 pl-8 border-l-4 border-zrp-gold bg-gray-50/50 p-6 rounded-r-2xl">
            <div class="text-zrp-blue text-base font-bold">
                <h3 class="italic text-base mb-3 flex items-center gap-2">
                    <i data-feather="file-text" class="w-4 h-4 text-zrp-gold"></i> BRIEF CIRCUMSTANCES
                </h3>
                <div class="italic leading-relaxed text-lg">"{{ $case->circumstances }}"</div>
            </div>
        </div>
    </div>

    {{-- BODY GRID --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-12">

        {{-- MAIN CONTENT (Span 8) --}}
        <div class="lg:col-span-8 space-y-12">


           {{-- 1. Persons Involved (Role-Based Sections) --}}
<section>
    <h3 class="text-base font-black text-zrp-blue uppercase tracking-widest mb-6 flex items-center gap-2 border-b-2 border-zrp-gold pb-2 w-fit">
        <i data-feather="users" class="w-5 h-5"></i> Persons Involved
    </h3>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        @forelse($case->people as $person)
            <div class="bg-white border-2 border-gray-100 rounded-2xl p-6 hover:border-zrp-blue transition-all shadow-sm">
                {{-- Primary Heading: The Role --}}
                <div class="mb-4">
                    <span class="text-2xl font-black text-zrp-blue uppercase tracking-tight italic">
                        {{ $person->pivot->role }}
                    </span>
                </div>

                {{-- Subject Details: Name and ID --}}
                <div class="text-zrp-blue uppercase text-base font-bold italic space-y-2">
                    <p class="text-sm not-italic font-bold flex flex-wrap items-center gap-2">
                        <span class="text-gray-400">NAME:</span>
                        <span class="text-zrp-blue">{{ $person->full_name }}</span>

                        <span class="mx-2 text-gray-300">|</span>

                        <span class="text-gray-400">ID:</span>
                        @if(strtoupper($person->id_number) === 'UNKNOWN')
                            <span class="text-orange-600 bg-orange-50 px-2 py-0.5 rounded text-[10px] font-black">NOT PROVIDED</span>
                        @else
                            <span class="text-zrp-blue">{{ $person->id_number }}</span>
                        @endif
                    </p>

                    {{-- Secondary Details --}}
                    <div class="pt-2 border-t border-gray-50 space-y-1">
                        <p class="text-[11px] text-gray-400"><span class="font-black">ADDRESS:</span> {{ $person->address ?: 'NOT RECORDED' }}</p>
                        <p class="text-[11px] text-gray-400"><span class="font-black">CONTACT:</span> {{ $person->phone_number ?: 'N/A' }}</p>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-2 py-8 text-center bg-gray-50 rounded-2xl border-2 border-dashed border-gray-200">
                <p class="text-base italic font-bold text-gray-400 uppercase">No persons recorded in this case.</p>
            </div>
        @endforelse
    </div>
</section>
            {{-- 2. Case Logs --}}
            <section>
                <div class="flex justify-between items-end mb-4 border-b-2 border-zrp-gold pb-2">
                    <h3 class="text-base font-black text-zrp-blue uppercase tracking-widest flex items-center gap-2">
                        <i data-feather="activity" class="w-5 h-5"></i> Case Audit Trail
                    </h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-zrp-blue uppercase text-sm font-bold italic">
                        <thead>
                            <tr class="text-[10px] text-gray-400">
                                <th class="pb-2">Timestamp</th>
                                <th class="pb-2">Officer</th>
                                <th class="pb-2">Action</th>
                                <th class="pb-2">Details</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($case->logs as $log)
                            <tr class="hover:bg-gray-50/50">
                                <td class="py-3 text-xs">{{ $log->created_at->format('d/m H:i') }}</td>
                                <td class="py-3 text-xs">{{ $log->user->name ?? 'SYSTEM' }}</td>
                                <td class="py-3">
                                    <span class="px-2 py-0.5 rounded text-[9px] font-black {{ $log->action === 'EVOLVED' ? 'bg-orange-500 text-white' : 'bg-gray-200' }}">
                                        {{ $log->action }}
                                    </span>
                                </td>
                                <td class="py-3 text-xs normal-case italic text-gray-600">{{ $log->description }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </section>

            {{-- 3. Media --}}
            <section>
                <h3 class="text-base font-black text-zrp-blue uppercase tracking-widest mb-6 flex items-center gap-2 border-b-2 border-zrp-gold pb-2 w-fit">
                    <i data-feather="camera" class="w-5 h-5"></i> Uploaded Media
                </h3>
                @if($case->media->count() > 0)
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        @foreach($case->media as $item)
                        <div class="group relative aspect-square bg-gray-100 rounded-lg overflow-hidden border-2 border-zrp-blue/10">
                            <img src="{{ asset('storage/' . $item->file_path) }}"
                                 class="w-full h-full object-cover grayscale group-hover:grayscale-0 transition-all duration-500">
                            <div class="absolute bottom-0 left-0 right-0 p-2 bg-zrp-blue/80 text-[8px] text-white opacity-0 group-hover:opacity-100 transition-opacity">
                                {{ $item->file_name }}
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="p-12 text-center bg-gray-50 rounded-2xl border-2 border-dashed border-gray-200">
                        <i data-feather="image" class="w-12 h-12 text-gray-300 mx-auto mb-3"></i>
                        <p class="text-base italic font-bold text-gray-400 uppercase">Awaiting forensic upload...</p>
                    </div>
                @endif
            </section>
        </div>

        {{-- SIDEBAR ACTIONS --}}
        <div class="lg:col-span-4">
            <div class="sticky top-6 space-y-6">
                {{-- Action: PDF --}}
                <a href="{{ route('admin.cases.export-pdf', $case) }}"
                   class="flex items-center justify-center gap-2 w-full py-4 bg-zrp-blue text-white rounded-xl font-bold text-base uppercase italic hover:bg-black transition-all shadow-lg transform hover:-translate-y-1">
                    <i data-feather="printer" class="w-5 h-5"></i> Generate Exhibit Bundle
                </a>

                {{-- Action: Reassign --}}
                <div class="border-2 border-zrp-gold p-6 rounded-2xl bg-white shadow-xl shadow-zrp-blue/5">
                    <div class="flex items-center gap-2 mb-4">
                        <i data-feather="refresh-cw" class="w-4 h-4 text-zrp-gold"></i>
                        <h4 class="text-base font-black text-zrp-blue uppercase">Reassign Photographer</h4>
                    </div>
                    <form action="{{ route('admin.cases.reassign', $case) }}" method="POST" class="space-y-4">
                        @csrf
                        @method('PATCH')
                        <select name="photographer_id" class="w-full bg-gray-50 border-2 border-gray-200 rounded-lg px-3 py-3 text-xs font-bold text-zrp-blue uppercase italic outline-none focus:border-zrp-blue">
                            @foreach($photographers as $p)
                                <option value="{{ $p->id }}" {{ $case->photographer_id == $p->id ? 'selected' : '' }}>
                                    {{ $p->surname }} ({{ $p->force_number }})
                                </option>
                            @endforeach
                        </select>
                        <input type="text" name="reassignment_reason" required placeholder="OFFICIAL REASON FOR REASSIGNMENT"
                               class="w-full bg-gray-50 border-2 border-gray-200 rounded-lg px-3 py-3 text-[10px] font-bold text-zrp-blue uppercase italic outline-none focus:border-zrp-blue">
                        <button type="submit" class="w-full py-3 bg-zrp-gold text-zrp-blue font-black rounded-xl text-xs uppercase italic hover:brightness-105 transition-all">
                            Update Personnel
                        </button>
                    </form>
                </div>

                {{-- Metadata Mini-Card --}}
                <div class="p-6 bg-zrp-blue/5 rounded-2xl border border-zrp-blue/10">
                    <h5 class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-3">System Metadata</h5>
                    <div class="space-y-2 text-[10px] text-zrp-blue font-bold uppercase italic">
                        <div class="flex justify-between"><span>Case Version:</span> <span>1.0.4-F</span></div>
                        <div class="flex justify-between">
                            <span>Last Activity:</span>
                            <span>
                                {{ $case->logs->sortByDesc('created_at')->first()?->created_at->diffForHumans() ?? 'No activity recorded' }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
