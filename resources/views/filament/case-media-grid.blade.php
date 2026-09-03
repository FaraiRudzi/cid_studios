@php
    $items = $getState() ?? [];
    $grouped = collect($items)->groupBy(fn ($item) => $item->title ?? 'Untitled Media');
@endphp

<div class="case-media-grid">
    @forelse($grouped as $title => $mediaGroup)
        <div class="case-media-group">
            <div class="case-media-header">{{ $title }}</div>
            <div class="case-media-items">
                @foreach($mediaGroup as $media)
                    @php
                        $paths = $media->getFilePaths();
                    @endphp

                    @foreach($paths as $path)
                        @php
                            $normalized = is_string($path) ? trim($path) : null;
                            $fullUrl = $normalized ? Storage::disk('public')->url($normalized) : null;
                            $isVideo = (str_contains((string) ($media->file_type ?? ''), 'video') || preg_match('/\.(mp4|mov|avi|m4v|webm|mkv)$/i', (string) $normalized))
                                ? true
                                : false;
                        @endphp

                        @if($normalized && $fullUrl)
                            <div class="case-media-item">
                                @if($isVideo)
                                    <video controls preload="metadata" src="{{ $fullUrl }}"></video>
                                @else
                                    <img src="{{ $fullUrl }}" alt="{{ $title }}" loading="lazy" />
                                @endif
                            </div>
                        @endif
                    @endforeach
                @endforeach
            </div>
        </div>
    @empty
        <div class="case-media-fallback">No media uploaded.</div>
    @endforelse
</div>
