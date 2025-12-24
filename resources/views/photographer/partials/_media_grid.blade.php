<section class="mb-10">
    <div class="flex items-center gap-4 mb-6">
        <h3 class="text-[11px] font-black text-zrp-blue uppercase tracking-widest">{{ $title }}</h3>
        <div class="h-[1px] bg-gray-100 w-full"></div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        @foreach($media as $item)
        <div class="bg-white border border-gray-100 rounded-3xl p-3 shadow-sm group">
            <div class="aspect-video rounded-2xl overflow-hidden mb-3 relative">
                <img src="{{ asset('storage/' . $item->path) }}" class="w-full h-full object-cover">

                {{-- REMOVE MEDIA BUTTON --}}
                <form action="{{ route('photographer.case.media.destroy', [$case, $item]) }}" method="POST"
                      onsubmit="return confirm('Are you sure you want to delete this forensic evidence?')"
                      class="absolute top-2 right-2 opacity-0 group-hover:opacity-100 transition-opacity">
                    @csrf @method('DELETE')
                    <button type="submit" class="bg-red-500 text-white p-2 rounded-full shadow-lg hover:bg-black transition-colors">
                        <i data-feather="trash-2" class="w-3 h-3"></i>
                    </button>
                </form>
            </div>
            <div class="px-2">
                <p class="text-[11px] font-bold text-gray-500 italic">"{{ $item->description }}"</p>
            </div>
        </div>
        @endforeach
    </div>
</section>
