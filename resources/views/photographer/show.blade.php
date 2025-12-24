@extends('layouts.photographer')

@section('title', 'Case Workspace: ' . $case->scene_reference_number)

@section('content')
<div class="max-w-7xl mx-auto pb-12 px-4">


    <div class="flex mb-8 border-b-2 border-gray-200 pb-6">
        <a href="{{ route('photographer.dashboard') }}"
           class="inline-flex items-center gap-2 px-5 py-2.5 bg-white dark:bg-gray-800 text-zrp-blue dark:text-gray-300 border-2 border-gray-100 dark:border-gray-700 rounded-xl font-bold text-sm hover:border-blue-500 transition-all shadow-sm">
            <i data-feather="arrow-left" class="w-4 h-4"></i>
            Back my Cases
        </a>
    </div>



    {{-- 1. HEADER: Case Status & Classification Transition --}}
    <div class="flex flex-col lg:flex-row justify-between items-start gap-6 mb-8 border-b-2 border-gray-100 pb-8">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <span class="h-2 w-2 rounded-full {{ $case->case_type == 'Murder' ? 'bg-red-500 animate-pulse' : 'bg-zrp-blue' }}"></span>
                <h2 class="text-3xl font-black text-zrp-blue uppercase italic tracking-tight">{{ $case->scene_reference_number }}</h2>
            </div>
            <div class="flex items-center gap-3">
                <span class="px-3 py-1 bg-zrp-blue text-zrp-gold text-[10px] font-black uppercase rounded-lg italic">
                    {{ $case->case_type }}
                </span>
                <span class="text-xs font-bold text-gray-400 uppercase italic">{{ $case->station->name }} | CR: {{ $case->reference_number }}</span>
            </div>
        </div>

        {{-- Case Transition --}}
        <div class="bg-zrp-blue/5 p-4 rounded-2xl border-2 border-zrp-blue/10 w-full lg:w-auto">
            <form action="{{ route('photographer.case.transition', $case) }}" method="POST" class="space-y-3">
                @csrf @method('PATCH')
                <div class="flex flex-col sm:flex-row gap-3">
                    <div class="space-y-1">
                        <label class="text-[9px] font-black text-zrp-blue uppercase px-1">Classification</label>
                        <select name="case_type" class="bg-white border-none rounded-xl px-4 py-2.5 text-xs font-bold text-zrp-blue w-full focus:ring-2 focus:ring-zrp-gold">
                            <option value="Sudden Death" {{ $case->case_type == 'Sudden Death' ? 'selected' : '' }}>Sudden Death</option>
                            <option value="Murder" {{ $case->case_type == 'Murder' ? 'selected' : '' }}>Murder</option>
                            <option value="Unlawful Entry" {{ $case->case_type == 'Unlawful Entry' ? 'selected' : '' }}>Unlawful Entry</option>
                            <option value="Theft" {{ $case->case_type == 'Theft' ? 'selected' : '' }}>Theft</option>
                            <option value="Assault" {{ $case->case_type == 'Assault' ? 'selected' : '' }}>Assault</option>
                        </select>
                    </div>
                    <div class="space-y-1">
                        <label class="text-[9px] font-black text-zrp-blue uppercase px-1">Forensic Finding</label>
                        <input type="text" name="cause_of_death" value="{{ $case->cause_of_death }}"
                               placeholder="Findings / Remarks..."
                               class="bg-white border-none rounded-xl px-4 py-2.5 text-xs font-bold text-zrp-blue w-full sm:w-64 focus:ring-2 focus:ring-zrp-gold shadow-sm">
                    </div>
                    <button type="submit" class="sm:mt-5 bg-zrp-blue text-white px-6 py-2.5 rounded-xl text-[10px] font-black uppercase hover:bg-black shadow-md transition-all">
                        Update
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">
        {{-- LEFT COLUMN: Subjects & Circumstances --}}
        <div class="lg:col-span-5 space-y-8">
            <section class="bg-white p-6 rounded-[2rem] shadow-sm border border-gray-100">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xs font-black text-zrp-blue uppercase italic">Brief Circumstances</h3>
                    <button type="submit" form="update-brief-form" class="text-[10px] font-black text-zrp-gold bg-zrp-blue px-3 py-1 rounded-lg uppercase">Update</button>
                </div>
                <form id="update-brief-form" action="{{ route('photographer.case.update', $case) }}" method="POST">
                    @csrf @method('PUT')
                    <textarea name="circumstances" rows="3" class="w-full bg-gray-50 border-none rounded-2xl p-4 text-xs font-bold text-zrp-blue italic outline-none focus:ring-2 focus:ring-zrp-gold">{{ $case->circumstances }}</textarea>
                </form>
            </section>

            <section class="space-y-4">
                <h3 class="text-xs font-black text-zrp-blue uppercase italic px-2">People Involved</h3>
                @foreach($case->people as $person)
                <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 group">
                    <form action="{{ route('photographer.case.person.update', [$case, $person]) }}" method="POST" class="space-y-4">
                        @csrf @method('PUT')
                        <div class="flex justify-between items-center">
                            <span class="px-3 py-1 bg-gray-100 rounded-lg text-[10px] font-black text-zrp-blue uppercase">{{ $person->pivot->role }}</span>
                            <button type="submit" class="text-[9px] font-black text-white bg-zrp-blue px-2 py-1 rounded hover:bg-black">Update Profile</button>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="col-span-1">
                                <label class="text-[8px] font-black text-gray-400 uppercase italic">First Name</label>
                                <input type="text" name="first_name" value="{{ $person->first_name }}" class="w-full bg-gray-50 border-none rounded-lg p-2 text-xs font-bold text-zrp-blue">
                            </div>
                            <div class="col-span-1">
                                <label class="text-[8px] font-black text-gray-400 uppercase italic">Surname</label>
                                <input type="text" name="surname" value="{{ $person->surname }}" class="w-full bg-gray-50 border-none rounded-lg p-2 text-xs font-bold text-zrp-blue">
                            </div>
                            <div class="col-span-2">
                                <label class="text-[8px] font-black text-gray-400 uppercase italic">Residential Address</label>
                                <textarea name="address" rows="2" class="w-full bg-gray-50 border-none rounded-lg p-2 text-xs font-bold text-zrp-blue">{{ $person->address }}</textarea>
                            </div>
                        </div>
                    </form>
                </div>
                @endforeach
            </section>
        </div>

        {{-- RIGHT COLUMN: Evidence Vault --}}
        <div class="lg:col-span-7 space-y-10">
            {{-- ADAPTIVE UPLOAD INTERFACE --}}
            <section class="bg-zrp-blue p-8 rounded-[2.5rem] shadow-xl text-white">
                <form action="{{ route('photographer.case.upload', $case) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="space-y-2">
                            <label class="text-[9px] font-black text-zrp-gold uppercase px-1 tracking-widest">Evidence Category</label>
                            <select name="category" class="w-full bg-white/10 border-none rounded-xl px-4 py-3 text-xs font-bold text-white focus:ring-2 focus:ring-zrp-gold">
                                @if(in_array($case->case_type, ['Sudden Death', 'Murder']))
                                    <option value="Scene of Crime (SDD)" class="text-black">Scene of Crime (SDD)</option>
                                    <option value="Post-Mortem" class="text-black">Post-Mortem</option>
                                    <option value="Indications" class="text-black">Indications</option>
                                @else
                                    <option value="Scene of Crime" class="text-black">Scene of Crime</option>
                                    <option value="Point of Entry" class="text-black">Point of Entry</option>
                                    <option value="Fingerprint Lifts" class="text-black">Fingerprint Lifts</option>
                                    <option value="Recovered Property" class="text-black">Recovered Property</option>
                                    <option value="General Evidence" class="text-black" selected>General Evidence</option>
                                @endif
                            </select>
                        </div>
                        <div class="space-y-2">
                            <label class="text-[9px] font-black text-zrp-gold uppercase px-1 tracking-widest">Media Files</label>
                            <input type="file" name="files[]" multiple required class="text-xs block w-full file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-black file:bg-zrp-gold file:text-zrp-blue">
                        </div>
                    </div>
                    <div class="space-y-2">
                        <label class="text-[9px] font-black text-zrp-gold uppercase px-1 tracking-widest">Exhibit Description</label>
                        <textarea name="description" required placeholder="Describe the findings..." class="w-full bg-white/10 border-none rounded-2xl p-4 text-xs font-bold text-white focus:ring-2 focus:ring-zrp-gold"></textarea>
                    </div>
                    <button type="submit" class="w-full py-4 bg-zrp-gold text-zrp-blue rounded-2xl font-black text-xs uppercase italic tracking-widest shadow-lg hover:bg-white transition-all">Secure To Dossier</button>
                </form>
            </section>

            {{-- ADAPTIVE GALLERY DISPLAY --}}
            @php
                if (in_array($case->case_type, ['Sudden Death', 'Murder'])) {
                    $phases = ['Scene of Crime (SDD)', 'Post-Mortem', 'Indications'];
                } else {
                    $phases = ['Scene of Crime', 'Point of Entry', 'Fingerprint Lifts', 'Recovered Property', 'General Evidence'];
                }
            @endphp

            @foreach($phases as $phase)
                @php $mediaItems = $case->media()->where('type', $phase)->latest()->get(); @endphp
                @if($mediaItems->count() > 0)
                <section class="space-y-4">
                    <div class="flex items-center gap-4">
                        <h3 class="text-[11px] font-black text-zrp-blue uppercase tracking-widest italic">{{ $phase }}</h3>
                        <div class="h-[1px] bg-gray-200 w-full"></div>
                        <span class="bg-zrp-blue text-zrp-gold px-2 py-0.5 rounded text-[9px] font-black">{{ $mediaItems->count() }}</span>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @foreach($mediaItems as $item)
                        <div class="bg-white border border-gray-100 rounded-[2rem] p-3 shadow-sm group relative overflow-hidden">
                            <div class="aspect-[4/3] rounded-2xl overflow-hidden mb-3 bg-gray-100 relative">
                                <img src="{{ asset('storage/' . $item->path) }}" class="w-full h-full object-cover">

                                {{-- DELETION OVERLAY --}}
                                <div class="absolute bottom-0 left-0 right-0 bg-red-600/90 backdrop-blur-sm p-3 flex justify-between items-center transform translate-y-full group-hover:translate-y-0 transition-transform duration-300">
                                    <span class="text-[8px] text-white font-black uppercase italic">Exhibit Protection</span>
                                    <form action="{{ route('photographer.case.media.destroy', [$case, $item]) }}" method="POST" onsubmit="return confirm('Purge this record?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-white hover:text-black"><i data-feather="trash-2" class="w-4 h-4"></i></button>
                                    </form>
                                </div>
                            </div>
                            <div class="px-2">
                                <p class="text-[11px] font-bold text-gray-600 italic leading-relaxed">"{{ $item->description }}"</p>
                                <p class="mt-2 text-[8px] font-black text-gray-300 uppercase tracking-tighter">{{ $item->created_at->format('H:i | d M Y') }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </section>
                @endif
            @endforeach
        </div>
    </div>

    {{-- AUDIT TRAIL --}}
    <section class="mt-16 bg-white border border-gray-100 rounded-[2.5rem] overflow-hidden shadow-sm">
        <div class="bg-gray-50 px-8 py-4 border-b border-gray-100 flex justify-between items-center">
            <h3 class="text-xs font-black text-zrp-blue uppercase italic">Dossier Access & Activity Log</h3>
            <i data-feather="database" class="w-4 h-4 text-zrp-gold"></i>
        </div>
        <table class="w-full text-left">
            <thead>
                <tr class="text-[9px] font-black text-zrp-blue uppercase tracking-widest border-b border-gray-50">
                    <th class="px-8 py-4">Timeline</th>
                    <th class="px-8 py-4">Action</th>
                    <th class="px-8 py-4">Operator</th>
                    <th class="px-8 py-4">Description</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @foreach($case->logs()->latest()->get() as $log)
                <tr class="hover:bg-blue-50/20 transition-colors">
                    <td class="px-8 py-4">
                        <div class="text-[10px] font-black text-zrp-blue">{{ $log->created_at->format('d M Y') }}</div>
                        <div class="text-[9px] text-gray-400 font-bold uppercase">{{ $log->created_at->format('H:i:s') }}</div>
                    </td>
                    <td class="px-8 py-4">
                        <span class="px-2 py-0.5 rounded text-[8px] font-black uppercase {{ $log->action == 'DELETED' ? 'bg-red-100 text-red-600' : 'bg-zrp-blue/10 text-zrp-blue' }}">
                            {{ $log->action }}
                        </span>
                    </td>
                    <td class="px-8 py-4">
                        <div class="text-[9px] font-black text-zrp-blue uppercase">{{ $log->operator_name }}</div>
                        <div class="text-[8px] text-gray-400 font-bold italic">{{ $log->role }}</div>
                    </td>
                    <td class="px-8 py-4 text-[11px] font-medium text-gray-600 italic">
                        {{ $log->description }}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </section>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const Toast = Swal.mixin({
            toast: true, position: 'top-end', showConfirmButton: false, timer: 4000,
            timerProgressBar: true, background: '#002e5d', color: '#ffffff'
        });
        @if(session('success')) Toast.fire({ icon: 'success', title: '{{ session('success') }}' }); @endif
    });
</script>
@endpush
