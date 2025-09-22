<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'prefix', // This will be 'informant', 'deceased', 'accused', etc.
    'label',  // The heading for this section, e.g., "Informant Details"
    'person' => null, // This will be the existing Person object, or null on the create page
]));

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

foreach (array_filter(([
    'prefix', // This will be 'informant', 'deceased', 'accused', etc.
    'label',  // The heading for this section, e.g., "Informant Details"
    'person' => null, // This will be the existing Person object, or null on the create page
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars); ?>

<div class="space-y-6">
    <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 border-b dark:border-gray-700 pb-2 pt-4"><?php echo e($label); ?></h3>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <label for="<?php echo e($prefix); ?>_first_name" class="block font-semibold text-gray-700 dark:text-gray-300 mb-1">First Name</label>
            
            <input type="text" id="<?php echo e($prefix); ?>_first_name" name="<?php echo e($prefix); ?>_first_name" value="<?php echo e(old($prefix.'_first_name', $person?->first_name)); ?>" class="form-input">
        </div>
        <div>
            <label for="<?php echo e($prefix); ?>_surname" class="block font-semibold text-gray-700 dark:text-gray-300 mb-1">Surname</label>
            <input type="text" id="<?php echo e($prefix); ?>_surname" name="<?php echo e($prefix); ?>_surname" value="<?php echo e(old($prefix.'_surname', $person?->surname)); ?>" class="form-input">
        </div>
        <div>
            <label for="<?php echo e($prefix); ?>_id_number" class="block font-semibold text-gray-700 dark:text-gray-300 mb-1">National ID Number</label>
            <input type="text" id="<?php echo e($prefix); ?>_id_number" name="<?php echo e($prefix); ?>_id_number" value="<?php echo e(old($prefix.'_id_number', $person?->id_number)); ?>" class="form-input" placeholder="Used to find or update people">
        </div>
        <div>
            <label for="<?php echo e($prefix); ?>_phone_number" class="block font-semibold text-gray-700 dark:text-gray-300 mb-1">Phone Number</label>
            <input type="text" id="<?php echo e($prefix); ?>_phone_number" name="<?php echo e($prefix); ?>_phone_number" value="<?php echo e(old($prefix.'_phone_number', $person?->phone_number)); ?>" class="form-input">
        </div>

        <div class="md:col-span-2">
            <label for="<?php echo e($prefix); ?>_address" class="block font-semibold text-gray-700 dark:text-gray-300 mb-1">Address</label>
           <textarea id="<?php echo e($prefix); ?>_address" name="<?php echo e($prefix); ?>_address" rows="2" class="form-input w-full"><?php echo e(old($prefix.'_address', $person?->address)); ?></textarea>

        </div>

         <div class="md:col-span-2">
            <label for="<?php echo e($prefix); ?>_email" class="block font-semibold text-gray-700 dark:text-gray-300 mb-1">Email (Optional)</label>
            <input type="email" id="<?php echo e($prefix); ?>_email" name="<?php echo e($prefix); ?>_email" value="<?php echo e(old($prefix.'_email', $person?->email)); ?>" class="form-input">
        </div>
    </div>
</div>
<?php /**PATH C:\inetpub\wwwroot\myproject\resources\views/components/person-fields.blade.php ENDPATH**/ ?>