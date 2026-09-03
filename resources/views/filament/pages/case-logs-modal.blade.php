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
            <td class="px-3 py-2">{{ $log->description }}</td>
            <td class="px-3 py-2">{{ $log->ip_address ?? 'N/A' }}</td>
        </tr>
    @empty
        <tr><td colspan="6" class="px-3 py-6 text-center text-gray-500">No audit logs recorded for this case.</td></tr>
    @endforelse
        </tbody>
    </table>
</div>