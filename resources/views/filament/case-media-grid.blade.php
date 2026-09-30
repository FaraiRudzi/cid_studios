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
                    @if($media->isRemoved())
                        <div class="case-media-item" style="padding:.75rem;font-size:.8rem;opacity:.8;">
                            Removed {{ $media->removed_at?->format('d M Y H:i') }}
                            @if($media->removal_reason) &mdash; {{ $media->removal_reason }} @endif
                            <br>The file and record are retained for the audit trail.
                        </div>
                        @continue
                    @endif

                    @foreach($media->getFilePaths() as $index => $path)
                        @php
                            $url = \App\Services\EvidenceStorage::urlFor($media, $index);
                            $hash = $media->hashFor($path);
                            $isVideo = str_contains((string) ($media->file_type ?? ''), 'video')
                                || preg_match('/\.(mp4|mov|avi|m4v|webm|mkv)$/i', (string) $path);
                        @endphp

                        <div class="case-media-item">
                            @if($isVideo)
                                <video controls preload="metadata" src="{{ $url }}"></video>
                            @else
                                <img src="{{ $url }}" alt="{{ $title }}" loading="lazy" />
                            @endif
                            <div style="font:11px/1.3 monospace;word-break:break-all;opacity:.7;margin-top:.25rem;">
                                SHA-256 {{ $hash ? substr($hash, 0, 16).'…' : 'not recorded (legacy item)' }}
                            </div>
                        </div>
                    @endforeach
                @endforeach
            </div>
        </div>
    @empty
        <div class="case-media-fallback">No media uploaded.</div>
    @endforelse
</div>
