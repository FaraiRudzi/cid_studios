<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Exhibit - {{ $case->scene_reference_number }}</title>
    <style>
        @page { margin: 0; size: A4 portrait; }
        * { box-sizing: border-box; }
        body { color: #111; font: 13px/1.35 Arial, sans-serif; margin: 0; }
        .page { border: 1px solid #111; break-after: page; height: 297mm; padding: 8mm; position: relative; width: 210mm; }
        .page:last-child { break-after: auto; }
        .header { border-bottom: 1px solid #111; display: table; min-height: 29mm; padding-bottom: 5mm; table-layout: fixed; width: 100%; }
        .header > div { display: table-cell; vertical-align: middle; width: 38%; }
        .header > div:nth-child(2) { width: 24%; }
        .badge { height: 25mm; max-width: 45mm; object-fit: contain; }
        .brand { font-weight: 700; line-height: 1.25; }
        .title { font-size: 28px; font-weight: 700; text-align: center; }
        .qr-block { text-align: center; }
        .qr { display: block; height: 27mm; margin: 0 auto; width: 27mm; }
        .qr-label { display: block; font-size: 11px; margin-top: 1mm; text-align: center; }
        .exhibit-grid { margin-top: 7mm; }
        .exhibit { border-bottom: 1px solid #aaa; height: 116mm; padding: 0 4mm 5mm; }
        .exhibit:last-child { border-bottom: 0; }
        .photo-wrap { align-items: center; display: flex; height: 91mm; justify-content: center; }
        .photo { display: block; max-height: 91mm; max-width: 100%; object-fit: contain; }
        .caption { margin-top: 3mm; }
        .exhibit-number { font-size: 15px; font-weight: 700; }
        .description { margin-top: 1mm; }
        .page-number { bottom: 5mm; position: absolute; right: 8mm; }
        .missing-badge { color: #666; font-size: 10px; }
        .preview-actions { background: #17202a; display: flex; gap: 10px; justify-content: center; padding: 12px; position: sticky; top: 0; z-index: 2; }
        .preview-actions a, .preview-actions button { background: #fff; border: 0; color: #17202a; cursor: pointer; font: inherit; padding: 8px 14px; text-decoration: none; }
        @media screen { body { background: #ddd; padding: 16px; } .page { background: #fff; margin: 0 auto 16px; max-width: 794px; width: 100%; } }
        @media print { .preview-actions { display: none; } body { padding: 0; } }
    </style>
</head>
<body>
@if($preview ?? false)
    <nav class="preview-actions no-print">
        <button type="button" onclick="window.print()">Print Exhibit</button>
        <a href="{{ route('cases.exhibit.pdf', ['case' => $case->getKey(), 'photos' => $photos]) }}">Download PDF</a>
    </nav>
@endif
@forelse(collect($exhibits)->chunk(2) as $pageExhibits)
    @php($pageQrCode = $pageExhibits->first()['qr_code'] ?? null)
    <main class="page">
        <header class="header">
            <div class="qr-block">
                @if(file_exists(public_path('badge.jpeg')))
                    <img class="badge" src="{{ 'data:image/jpeg;base64,'.base64_encode(file_get_contents(public_path('badge.jpeg'))) }}" alt="Zimbabwe Republic Police badge">
                @else
                    <div class="missing-badge">Badge asset: public/badge.jpeg</div>
                @endif
                <div class="brand">Zimbabwe Republic Police<br>CID Studios</div>
            </div>
            <div class="title">EXHIBIT</div>
            <div class="qr-block">
                <img class="qr" src="{{ $pageQrCode }}" alt="Case verification QR code">
                <div class="qr-label">Scan to verify</div>
            </div>
        </header>

        <div class="exhibit-grid">
            @foreach($pageExhibits as $index => $exhibit)
                <section class="exhibit">
                    <div class="photo-wrap">
                        <img class="photo" src="{{ $exhibit['image'] }}" alt="{{ $exhibit['media']->title }}">
                    </div>
                    <div class="caption">
                        <div class="exhibit-number">EXHIBIT {{ str_pad((string) ($loop->parent->index * 2 + $index + 1), 3, '0', STR_PAD_LEFT) }}</div>
                        <div class="description">{{ $exhibit['media']->description ?: $exhibit['media']->title }}</div>
                        <div style="font:7px/1.3 monospace;word-break:break-all;margin-top:3px;">
                            SHA-256: {{ $exhibit['hash'] }}<br>
                            @if(($exhibit['verified'] ?? null) === true)
                                Matches the hash recorded at upload.
                            @elseif(($exhibit['verified'] ?? null) === false)
                                WARNING: DOES NOT MATCH THE HASH RECORDED AT UPLOAD.
                            @else
                                No upload hash on record (legacy item).
                            @endif
                        </div>
                    </div>
                </section>
            @endforeach
        </div>

        <div class="page-number">Page {{ $loop->iteration }} of {{ ceil(count($exhibits) / 2) }}</div>
    </main>
@empty
    <p>No photographs were selected.</p>
@endforelse
</body>
</html>
