<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['person', 'label']));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter((['person', 'label']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars); ?>

<div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md">
    <h3 class="text-xl font-semibold mb-4 border-b dark:border-gray-700 pb-2 text-gray-800 dark:text-gray-200"><?php echo e($label); ?></h3>
    <dl class="grid grid-cols-[auto,1fr] gap-x-6 gap-y-3 text-base">
        <dt class="font-semibold text-gray-600 dark:text-gray-400">Full Name:</dt>
        <dd class="text-gray-900 dark:text-gray-100"><?php echo e($person?->fullName ?? 'N/A'); ?></dd>

        <dt class="font-semibold text-gray-600 dark:text-gray-400">ID Number:</dt>
        <dd class="text-gray-900 dark:text-gray-100"><?php echo e($person?->id_number ?? 'N/A'); ?></dd>
        <dt class="font-semibold text-gray-600 dark:text-gray-400">Address:</dt>
        <dd class="text-gray-900 dark:text-gray-100"><?php echo e($person?->address ?? 'N/A'); ?></dd>
        <dt class="font-semibold text-gray-600 dark:text-gray-400">Phone:</dt>
        <dd class="text-gray-900 dark:text-gray-100"><?php echo e($person?->phone_number ?? 'N/A'); ?></dd>
    </dl>
</div><?php /**PATH C:\inetpub\wwwroot\myproject\resources\views/components/person-show-card.blade.php ENDPATH**/ ?>