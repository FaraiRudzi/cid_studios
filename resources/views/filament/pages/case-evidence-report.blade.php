<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Case Evidence Report - {{ $case->scene_reference_number }}</title>
    <style>
        @page { margin: 18mm; }
        * { box-sizing: border-box; }
        body { color: #17202a; font: 12px/1.45 Arial, sans-serif; margin: 0; }
        h1, h2 { color: #111827; margin: 0 0 10px; }
        h1 { font-size: 22px; }
        h2 { border-bottom: 2px solid #17202a; font-size: 15px; margin-top: 24px; padding-bottom: 5px; }
        .header { border-bottom: 3px solid #b45309; margin-bottom: 18px; padding-bottom: 12px; }
        .muted { color: #59636e; }
        .grid { display: grid; gap: 8px 18px; grid-template-columns: repeat(2, minmax(0, 1fr)); }
        .field { border-bottom: 1px solid #d7dce1; padding: 5px 0; }
        .label { color: #59636e; font-size: 10px; text-transform: uppercase; }
        .value { font-weight: 600; }
        .person, .media { border: 1px solid #c7cdd4; break-inside: avoid; margin: 10px 0; padding: 10px; }
        .person h3, .media h3 { font-size: 13px; margin: 0 0 7px; }
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #c7cdd4; padding: 6px; text-align: left; vertical-align: top; }
        th { background: #eef1f4; font-size: 10px; text-transform: uppercase; }
        .evidence-image { border: 1px solid #c7cdd4; display: block; margin: 8px 0; max-height: 260px; max-width: 100%; object-fit: contain; }
        .hash { font: 10px/1.3 Consolas, monospace; word-break: break-all; }
        .footer { border-top: 1px solid #c7cdd4; color: #59636e; font-size: 10px; margin-top: 28px; padding-top: 8px; }
        @media print { .no-print { display: none; } }
    </style>
</head>
<body>
    <header class="header">
        <h1>Case Evidence Report</h1>
        <div class="muted">Generated {{ now()->format('d M Y H:i:s T') }}</div>
    </header>

    <h2>Case Identification</h2>
    <div class="grid">
        <div class="field"><div class="label">Scene reference</div><div class="value">{{ $case->scene_reference_number }}</div></div>
        <div class="field"><div class="label">CR number</div><div class="value">{{ $case->reference_number }}</div></div>
        <div class="field"><div class="label">Case type</div><div class="value">{{ $case->case_type }}</div></div>
        <div class="field"><div class="label">Status</div><div class="value">{{ $case->status }}</div></div>
        <div class="field"><div class="label">Station</div><div class="value">{{ $case->station?->name ?? 'Not recorded' }}</div></div>
        <div class="field"><div class="label">Assigned photographer</div><div class="value">{{ $case->photographer?->name ?? 'Not recorded' }}</div></div>
        <div class="field"><div class="label">Created by</div><div class="value">{{ $case->creator?->name ?? 'Not recorded' }}</div></div>
        <div class="field"><div class="label">Created at</div><div class="value">{{ $case->created_at?->format('d M Y H:i:s T') }}</div></div>
    </div>

    <h2>Case Circumstances</h2>
    <div class="field"><div class="label">Circumstances</div><div>{{ $case->circumstances ?: 'Not recorded' }}</div></div>
    <div class="field"><div class="label">Cause of death</div><div>{{ $case->cause_of_death ?: 'Not recorded' }}</div></div>

    <h2>People Involved ({{ $case->people->count() }})</h2>
    @forelse($case->people as $person)
        <section class="person">
            <h3>{{ trim(($person->first_name ?? '') . ' ' . ($person->surname ?? '')) ?: 'Unnamed person' }}</h3>
            <div class="grid">
                <div class="field"><div class="label">Role</div><div class="value">{{ ucfirst($person->pivot?->role ?? 'Not recorded') }}</div></div>
                <div class="field"><div class="label">ID number</div><div>{{ $person->id_number ?: 'Not recorded' }}</div></div>
                <div class="field"><div class="label">Gender</div><div>{{ $person->gender ?: 'Not recorded' }}</div></div>
                <div class="field"><div class="label">Phone</div><div>{{ $person->phone_number ?: 'Not recorded' }}</div></div>
                <div class="field"><div class="label">Email</div><div>{{ $person->email ?: 'Not recorded' }}</div></div>
                <div class="field"><div class="label">Address</div><div>{{ $person->address ?: 'Not recorded' }}</div></div>
                <div class="field"><div class="label">Case notes</div><div>{{ $person->pivot?->notes ?: 'Not recorded' }}</div></div>
            </div>
        </section>
    @empty
        <p class="muted">No people are linked to this case.</p>
    @endforelse

    <h2>Media Evidence ({{ $case->media->count() }})</h2>
    @forelse($case->media as $media)
        <section class="media">
            <h3>{{ $media->title }}@if($media->isRemoved()) (REMOVED {{ $media->removed_at?->format('d M Y H:i') }}: {{ $media->removal_reason }})@endif</h3>
            <div class="grid">
                <div class="field"><div class="label">Uploaded by</div><div>{{ $media->uploader?->name ?? 'Not recorded' }}</div></div>
                <div class="field"><div class="label">Uploaded at</div><div>{{ $media->created_at?->format('d M Y H:i:s T') }}</div></div>
            </div>
            @foreach($media->getFilePaths() as $path)
                @php
                    $evidenceDisk = \App\Services\EvidenceStorage::disk();
                    $exists = \App\Services\EvidenceStorage::isAcceptablePath($path) && $evidenceDisk->exists($path);
                    $absolutePath = $exists ? $evidenceDisk->path($path) : null;
                    $mime = $exists ? (mime_content_type($absolutePath) ?: 'application/octet-stream') : null;
                    $hash = $exists ? hash_file('sha256', $absolutePath) : null;
                    $recordedHash = $media->hashFor($path);
                    $isImage = $exists && str_starts_with($mime, 'image/');
                    $dataUri = $isImage ? 'data:' . $mime . ';base64,' . base64_encode(file_get_contents($absolutePath)) : null;
                @endphp
                <div class="field">
                    <div class="label">File path</div><div>{{ $path }}</div>
                    <div class="label">SHA-256 (now)</div><div class="hash">{{ $hash ?: 'File not available on server' }}</div>
                    <div class="label">SHA-256 (recorded)</div>
                    <div class="hash">
                        {{ $recordedHash ?: 'No hash recorded for this legacy item' }}
                        @if($hash && $recordedHash)
                            &mdash; <strong>{{ hash_equals($recordedHash, $hash) ? 'MATCH' : 'MISMATCH - FILE HAS CHANGED' }}</strong>
                        @endif
                    </div>
                    @if($dataUri)
                        <img class="evidence-image" src="{{ $dataUri }}" alt="{{ $media->title }}">
                    @endif
                </div>
            @endforeach
        </section>
    @empty
        <p class="muted">No media is linked to this case.</p>
    @endforelse

    <h2>Audit Trail</h2>
    <table>
        <thead><tr><th>Date and time</th><th>Action</th><th>Actor</th><th>Role</th><th>Description</th><th>IP</th></tr></thead>
        <tbody>
        @forelse($case->logs as $log)
            <tr>
                <td>{{ $log->created_at?->format('d M Y H:i:s T') }}</td>
                <td>{{ $log->action }}</td>
                <td>{{ $log->user?->name ?? 'System' }}</td>
                <td>{{ $log->role }}</td>
                <td>{{ $log->description }}</td>
                <td>{{ $log->ip_address ?: 'N/A' }}</td>
            </tr>
        @empty
            <tr><td colspan="6">No audit logs recorded for this case.</td></tr>
        @endforelse
        </tbody>
    </table>

    <div class="footer">This report contains case metadata, linked persons, media file hashes, available media previews, and the recorded audit trail. Print this page or use the browser's "Save as PDF" function for a PDF copy.</div>
</body>
</html>
