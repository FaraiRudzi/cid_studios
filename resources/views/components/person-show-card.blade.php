@props(['person', 'label'])

<div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md">
    <h3 class="text-xl font-semibold mb-4 border-b dark:border-gray-700 pb-2 text-gray-800 dark:text-gray-200">{{ $label }}</h3>
    <dl class="grid grid-cols-[auto,1fr] gap-x-6 gap-y-3 text-base">
        <dt class="font-semibold text-gray-600 dark:text-gray-400">Full Name:</dt>
        <dd class="text-gray-900 dark:text-gray-100">{{ $person?->fullName ?? 'N/A' }}</dd>

        <dt class="font-semibold text-gray-600 dark:text-gray-400">ID Number:</dt>
        <dd class="text-gray-900 dark:text-gray-100">{{ $person?->id_number ?? 'N/A' }}</dd>
        <dt class="font-semibold text-gray-600 dark:text-gray-400">Address:</dt>
        <dd class="text-gray-900 dark:text-gray-100">{{ $person?->address ?? 'N/A' }}</dd>
        <dt class="font-semibold text-gray-600 dark:text-gray-400">Phone:</dt>
        <dd class="text-gray-900 dark:text-gray-100">{{ $person?->phone_number ?? 'N/A' }}</dd>
    </dl>
</div>