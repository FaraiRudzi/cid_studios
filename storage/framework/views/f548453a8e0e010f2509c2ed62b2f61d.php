

<?php $__env->startSection('title', 'Add New Case'); ?>

<?php $__env->startSection('content'); ?>
<div class="container mx-auto">
    
    <div class="max-w-4xl mx-auto mb-6 flex justify-between items-center">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-200">Add New Case</h1>
        <a href="<?php echo e(route('admin.cases.index')); ?>" class="text-blue-600 dark:text-blue-400 hover:underline flex items-center gap-2">
            <i data-feather="arrow-left" class="w-5 h-5"></i> Back to Case List
        </a>
    </div>

    
    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md max-w-4xl mx-auto">
        
        <form action="<?php echo e(route('admin.cases.store')); ?>" method="POST" x-data="{ case_type: '<?php echo e(old('case_type', '')); ?>' }">
            <?php echo csrf_field(); ?>
            
            
            <div class="space-y-6">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 border-b dark:border-gray-700 pb-2">Core Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="case_type" class="block font-semibold text-gray-700 dark:text-gray-300 mb-1">Case Type</label>
                        <select name="case_type" id="case_type" x-model="case_type" required class="form-select">
                            <option value="">-- Choose Case Type --</option>
                            <?php $__currentLoopData = $caseTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($type); ?>" <?php if(old('case_type') == $type): echo 'selected'; endif; ?>><?php echo e($type); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div>
                        <label for="station_id" class="block font-semibold text-gray-700 dark:text-gray-300 mb-1">Station</label>
                        <select name="station_id" id="station_id" required>
                            <option value="">-- Choose Station --</option>
                            <?php $__currentLoopData = $stations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $station): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($station->id); ?>" <?php if(old('station_id') == $station->id): echo 'selected'; endif; ?>><?php echo e($station->name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    
                    <div class="md:col-span-2">
                        <label for="reference_number" class="block font-semibold text-gray-700 dark:text-gray-300 mb-1">Case Reference Number</label>
                        <input type="text" id="reference_number" name="reference_number" value="<?php echo e(old('reference_number')); ?>" required class="form-input" placeholder="e.g. CID/MURDER/01/01/2025">
                    </div>

                    <div class="md:col-span-2">
                        <label for="circumstances" class="block font-semibold text-gray-700 dark:text-gray-300 mb-1">Circumstances</label>
                        <textarea id="circumstances" name="circumstances" rows="4" required class="form-textarea" placeholder="Brief background of the case"><?php echo e(old('circumstances')); ?></textarea>
                    </div>
                    <div>
                        <label for="photographer_id" class="block font-semibold text-gray-700 dark:text-gray-300 mb-1">Assign Photographer</label>
                        <select name="photographer_id" id="photographer_id" required>
                            <option value="">-- Choose Photographer --</option>
                            <?php $__currentLoopData = $photographers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $photographer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($photographer->id); ?>" <?php if(old('photographer_id') == $photographer->id): echo 'selected'; endif; ?>>
                                    <?php echo e($photographer->surname); ?> (<?php echo e($photographer->force_number); ?>)
                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div x-show="['Murder', 'Sudden Death'].includes(case_type)" x-cloak>
                        <label for="cause_of_death" class="block font-semibold text-gray-700 dark:text-gray-300 mb-1">Cause of Death (Optional)</label>
                        <input type="text" id="cause_of_death" name="cause_of_death" value="<?php echo e(old('cause_of_death')); ?>" class="form-input">
                    </div>
                </div>
            </div>

            
            <div x-show="['Murder', 'Sudden Death', 'Exhibits', 'Dying Declarations', 'Exhumations'].includes(case_type)" x-cloak>
                <?php if (isset($component)) { $__componentOriginalcec4d2a1b81cf4ad6b7ce7d8701d03bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalcec4d2a1b81cf4ad6b7ce7d8701d03bc = $attributes; } ?>
<?php $component = App\View\Components\PersonFields::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('person-fields'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\PersonFields::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['prefix' => 'informant','label' => 'Informant Details']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalcec4d2a1b81cf4ad6b7ce7d8701d03bc)): ?>
<?php $attributes = $__attributesOriginalcec4d2a1b81cf4ad6b7ce7d8701d03bc; ?>
<?php unset($__attributesOriginalcec4d2a1b81cf4ad6b7ce7d8701d03bc); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcec4d2a1b81cf4ad6b7ce7d8701d03bc)): ?>
<?php $component = $__componentOriginalcec4d2a1b81cf4ad6b7ce7d8701d03bc; ?>
<?php unset($__componentOriginalcec4d2a1b81cf4ad6b7ce7d8701d03bc); ?>
<?php endif; ?>
            </div>
            
            <div x-show="['Murder', 'Sudden Death'].includes(case_type)" x-cloak>
                <?php if (isset($component)) { $__componentOriginalcec4d2a1b81cf4ad6b7ce7d8701d03bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalcec4d2a1b81cf4ad6b7ce7d8701d03bc = $attributes; } ?>
<?php $component = App\View\Components\PersonFields::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('person-fields'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\PersonFields::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['prefix' => 'deceased','label' => 'Deceased Details (if known)']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalcec4d2a1b81cf4ad6b7ce7d8701d03bc)): ?>
<?php $attributes = $__attributesOriginalcec4d2a1b81cf4ad6b7ce7d8701d03bc; ?>
<?php unset($__attributesOriginalcec4d2a1b81cf4ad6b7ce7d8701d03bc); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcec4d2a1b81cf4ad6b7ce7d8701d03bc)): ?>
<?php $component = $__componentOriginalcec4d2a1b81cf4ad6b7ce7d8701d03bc; ?>
<?php unset($__componentOriginalcec4d2a1b81cf4ad6b7ce7d8701d03bc); ?>
<?php endif; ?>
            </div>

            <div x-show="['Identification Parade', 'Indications', 'Warned and Cautioned Statement', 'Murder', 'Exhibits'].includes(case_type)" x-cloak>
                <?php if (isset($component)) { $__componentOriginalcec4d2a1b81cf4ad6b7ce7d8701d03bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalcec4d2a1b81cf4ad6b7ce7d8701d03bc = $attributes; } ?>
<?php $component = App\View\Components\PersonFields::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('person-fields'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\PersonFields::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['prefix' => 'accused','label' => 'Accused Details']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalcec4d2a1b81cf4ad6b7ce7d8701d03bc)): ?>
<?php $attributes = $__attributesOriginalcec4d2a1b81cf4ad6b7ce7d8701d03bc; ?>
<?php unset($__attributesOriginalcec4d2a1b81cf4ad6b7ce7d8701d03bc); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcec4d2a1b81cf4ad6b7ce7d8701d03bc)): ?>
<?php $component = $__componentOriginalcec4d2a1b81cf4ad6b7ce7d8701d03bc; ?>
<?php unset($__componentOriginalcec4d2a1b81cf4ad6b7ce7d8701d03bc); ?>
<?php endif; ?>
            </div>

            <div x-show="['Identification Parade', 'Exhibits'].includes(case_type)" x-cloak>
                <?php if (isset($component)) { $__componentOriginalcec4d2a1b81cf4ad6b7ce7d8701d03bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalcec4d2a1b81cf4ad6b7ce7d8701d03bc = $attributes; } ?>
<?php $component = App\View\Components\PersonFields::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('person-fields'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\PersonFields::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['prefix' => 'complainant','label' => 'Complainant Details']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalcec4d2a1b81cf4ad6b7ce7d8701d03bc)): ?>
<?php $attributes = $__attributesOriginalcec4d2a1b81cf4ad6b7ce7d8701d03bc; ?>
<?php unset($__attributesOriginalcec4d2a1b81cf4ad6b7ce7d8701d03bc); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcec4d2a1b81cf4ad6b7ce7d8701d03bc)): ?>
<?php $component = $__componentOriginalcec4d2a1b81cf4ad6b7ce7d8701d03bc; ?>
<?php unset($__componentOriginalcec4d2a1b81cf4ad6b7ce7d8701d03bc); ?>
<?php endif; ?>
            </div>
            
            
            <div class="pt-6 flex items-center gap-4 border-t dark:border-gray-700 mt-6">
                <button type="submit" class="bg-blue-600 text-white px-5 py-2 rounded-md hover:bg-blue-700 font-semibold flex items-center gap-2">
                    <i data-feather="save" class="w-5 h-5"></i>
                    Save Case
                </button>
                <a href="<?php echo e(route('admin.cases.index')); ?>" class="text-gray-600 dark:text-gray-400 hover:underline">Cancel</a>
            </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?> 



<?php $__env->startPush('scripts'); ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize TomSelect for searchable dropdowns
        new TomSelect("#station_id",{ create: false, sortField: { field: "text", direction: "asc" } });
        new TomSelect("#photographer_id",{ create: false, sortField: { field: "text", direction: "asc" } });

        // THE FIX IS HERE: This will display validation errors in a SweetAlert pop-up
        <?php if($errors->any()): ?>
            Swal.fire({
                icon: 'error',
                title: 'Validation Error',
                html: `<?php echo implode('<br>', $errors->all()); ?>`
            });
        <?php endif; ?>
    });
</script>


<style>
    .form-input, .form-select, .form-textarea { width: 100%; border-radius: 0.375rem; border: 1px solid #D1D5DB; box-shadow: 0 1px 2px 0 rgb(0 0 0 / 0.05); }
    .dark .form-input, .dark .form-select, .dark .form-textarea { background-color: #374151; border-color: #4B5563; color: #D1D5DB; }
    .ts-wrapper { width: 100%; }
</style>
<?php $__env->stopPush(); ?>


<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\inetpub\wwwroot\myproject\resources\views/admin/cases/create.blade.php ENDPATH**/ ?>