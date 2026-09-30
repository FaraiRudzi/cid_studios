<div class="overflow-x-auto">
    <table class="w-full text-left text-sm">
        <thead class="border-b border-gray-300 text-xs uppercase text-gray-500">
            <tr>
                <th class="px-3 py-2">Date and time</th>
                <th class="px-3 py-2">Activity</th>
                <th class="px-3 py-2">Actor</th>
                <th class="px-3 py-2">Role</th>
                <th class="px-3 py-2">Description</th>
                <th class="px-3 py-2">IP address</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
    @forelse($logs as $log)
        <tr>
            <td class="whitespace-nowrap px-3 py-2">{{ $log->created_at?->format('d M Y H:i:s') }}</td>
            <td class="px-3 py-2 font-semibold text-amber-700">{{ $log->action }}</td>
            <td class="px-3 py-2">{{ $log->user?->name ?? 'System' }}</td>
            <td class="px-3 py-2">{{ $log->role }}</td>
            <td class="px-3 py-2">
                {{ $log->description }}

                @if(! empty($log->metadata['reason']))
                    <div class="text-xs text-gray-500">Reason: {{ $log->metadata['reason'] }}</div>
                @endif

                @foreach(($log->metadata['changes'] ?? []) as $field => $change)
                    <div class="text-xs text-gray-500">
                        <strong>{{ str_replace('_', ' ', $field) }}</strong>:
                        {{ \Illuminate\Support\Str::limit((string) ($change['from'] ?? '—'), 120) }}
                        &rarr;
                        {{ \Illuminate\Support\Str::limit((string) ($change['to'] ?? '—'), 120) }}
                    </div>
                @endforeach

                @foreach(($log->metadata['files'] ?? []) as $file)
                    <div class="text-xs text-gray-500" style="word-break:break-all;">
                        {{ basename($file['path'] ?? '') }}
                        @if(! empty($file['sha256'])) &middot; SHA-256 {{ substr($file['sha256'], 0, 16) }}… @endif
                        @if(array_key_exists('matches_upload_hash', $file))
                            &middot;
                            @if($file['matches_upload_hash'] === true) matches upload hash
                            @elseif($file['matches_upload_hash'] === false) DOES NOT MATCH upload hash
                            @else no upload hash on record @endif
                        @endif
                    </div>
                @endforeach
            </td>
            <td class="px-3 py-2">{{ $log->ip_address ?? 'N/A' }}</td>
        </tr>
    @empty
        <tr><td colspan="6" class="px-3 py-6 text-center text-gray-500">No audit logs recorded for this case.</td></tr>
    @endforelse
        </tbody>
    </table>
</div>
