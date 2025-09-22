<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['person', 'label', 'prefix']));

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

foreach (array_filter((['person', 'label', 'prefix']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars); ?>

<div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md">
    
    <form action="<?php echo e(route('photographer.case.person.update', ['case' => $person->pivot->case_id, 'person' => $person->id])); ?>" method="POST">
        <?php echo csrf_field(); ?>
        <?php echo method_field('PUT'); ?>
        
        <div class="flex justify-between items-center mb-4 border-b dark:border-gray-700 pb-2">
            <h3 class="text-xl font-semibold text-gray-800 dark:text-gray-200"><?php echo e($label); ?></h3>
            <button type="submit" class="bg-blue-600 text-white px-3 py-1 rounded-md hover:bg-blue-700 text-sm font-semibold flex items-center gap-1">
                <i data-feather="save" class="w-4 h-4"></i> Save
            </button>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            
            <div><label for="first_name_<?php echo e($person->id); ?>">First Name</label><input type="text" id="first_name_<?php echo e($person->id); ?>" name="first_name" value="<?php echo e(old('first_name', $person->first_name)); ?>" class="form-input"></div>
            <div><label for="surname_<?php echo e($person->id); ?>">Surname</label><input type="text" id="surname_<?php echo e($person->id); ?>" name="surname" value="<?php echo e(old('surname', $person->surname)); ?>" class="form-input"></div>
            <div><label for="id_number_<?php echo e($person->id); ?>">National ID Number</label><input type="text" id="id_number_<?php echo e($person->id); ?>" name="id_number" value="<?php echo e(old('id_number', $person->id_number)); ?>" class="form-input"></div>
            <div><label for="phone_number_<?php echo e($person->id); ?>">Phone Number</label><input type="text" id="phone_number_<?php echo e($person->id); ?>" name="phone_number" value="<?php echo e(old('phone_number', $person->phone_number)); ?>" class="form-input"></div>
            <div class="md:col-span-2"><label for="address_<?php echo e($person->id); ?>">Address</label><input type="text" id="address_<?php echo e($person->id); ?>" name="address" value="<?php echo e(old('address', $person->address)); ?>" class="form-input"></div>
            <div class="md:col-span-2"><label for="email_<?php echo e($person->id); ?>">Email (Optional)</label><input type="email" id="email_<?php echo e($person->id); ?>" name="email" value="<?php echo e(old('email', $person->email)); ?>" class="form-input"></div>
        </div>
    </form>
</div><?php /**PATH C:\inetpub\wwwroot\myproject\resources\views/components/person-edit-card.blade.php ENDPATH**/ ?>