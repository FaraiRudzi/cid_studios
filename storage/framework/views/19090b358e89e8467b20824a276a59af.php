
<?php $__env->startSection('title', 'Case Workspace'); ?>

<?php $__env->startSection('content'); ?>
<div class="container mx-auto">

    
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-200">Case Workspace</h1>
        <p class="text-sm text-gray-500 dark:text-gray-400">Scene Ref: #<?php echo e($case->scene_reference_number); ?> | Case Ref: #<?php echo e($case->reference_number); ?></p>
    </div>

    <!-- Main Grid Layout -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    
        <!-- Left Column: Details (each is now a separate form/component) -->
        <div class="lg:col-span-2 space-y-8">

            <!-- Core Information Card (is now its own form) -->
            <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md">
                <form action="<?php echo e(route('photographer.case.update', $case)); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PUT'); ?>
                    <div class="flex justify-between items-center mb-4 border-b dark:border-gray-700 pb-2">
                        <h3 class="text-xl font-semibold text-gray-800 dark:text-gray-200">Core Information</h3>
                        <button type="submit" class="bg-blue-600 text-white px-3 py-1 rounded-md hover:bg-blue-700 text-sm font-semibold flex items-center gap-1">
                            <i data-feather="save" class="w-4 h-4"></i> Save
                        </button>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div><label class="block font-semibold text-sm text-gray-700 dark:text-gray-300 mb-1">Station (Read-only)</label><p class="form-input bg-gray-100 dark:bg-gray-700/50 p-2"><?php echo e($case->station?->name ?? 'N/A'); ?></p></div>
                        <div><label class="block font-semibold text-sm text-gray-700 dark:text-gray-300 mb-1">Case Type (Read-only)</label><p class="form-input bg-gray-100 dark:bg-gray-700/50 p-2"><?php echo e($case->case_type); ?></p></div>
                        <div class="md:col-span-2"><label for="circumstances" class="block font-semibold text-sm text-gray-700 dark:text-gray-300 mb-1">Circumstances</label><textarea id="circumstances" name="circumstances" rows="4" required class="form-textarea p-2"><?php echo e(old('circumstances', $case->circumstances)); ?></textarea></div>
                    </div>
                </form>
            </div>
            
            
            <?php if($case->deceased): ?> <?php if (isset($component)) { $__componentOriginalc583b686a1a1d2bfc7c37d4c64d075b1 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc583b686a1a1d2bfc7c37d4c64d075b1 = $attributes; } ?>
<?php $component = App\View\Components\PersonEditCard::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('person-edit-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\PersonEditCard::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['person' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($case->deceased),'label' => 'Deceased Details (Editable)']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalc583b686a1a1d2bfc7c37d4c64d075b1)): ?>
<?php $attributes = $__attributesOriginalc583b686a1a1d2bfc7c37d4c64d075b1; ?>
<?php unset($__attributesOriginalc583b686a1a1d2bfc7c37d4c64d075b1); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc583b686a1a1d2bfc7c37d4c64d075b1)): ?>
<?php $component = $__componentOriginalc583b686a1a1d2bfc7c37d4c64d075b1; ?>
<?php unset($__componentOriginalc583b686a1a1d2bfc7c37d4c64d075b1); ?>
<?php endif; ?> <?php endif; ?>
            <?php if($case->accused->isNotEmpty()): ?>
                <?php $__currentLoopData = $case->accused; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $accusedPerson): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php if (isset($component)) { $__componentOriginalc583b686a1a1d2bfc7c37d4c64d075b1 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc583b686a1a1d2bfc7c37d4c64d075b1 = $attributes; } ?>
<?php $component = App\View\Components\PersonEditCard::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('person-edit-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\PersonEditCard::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['person' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($accusedPerson),'prefix' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute('accused_' . $index),'label' => 'Accused #'.e($index + 1).' Details (Editable)']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalc583b686a1a1d2bfc7c37d4c64d075b1)): ?>
<?php $attributes = $__attributesOriginalc583b686a1a1d2bfc7c37d4c64d075b1; ?>
<?php unset($__attributesOriginalc583b686a1a1d2bfc7c37d4c64d075b1); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc583b686a1a1d2bfc7c37d4c64d075b1)): ?>
<?php $component = $__componentOriginalc583b686a1a1d2bfc7c37d4c64d075b1; ?>
<?php unset($__componentOriginalc583b686a1a1d2bfc7c37d4c64d075b1); ?>
<?php endif; ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php endif; ?>
            <?php if($case->informant): ?> <?php if (isset($component)) { $__componentOriginal5566490223217002dd49001ada8a35db = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5566490223217002dd49001ada8a35db = $attributes; } ?>
<?php $component = App\View\Components\PersonShowCard::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('person-show-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\PersonShowCard::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['person' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($case->informant),'label' => 'Informant Details (Read-only)']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5566490223217002dd49001ada8a35db)): ?>
<?php $attributes = $__attributesOriginal5566490223217002dd49001ada8a35db; ?>
<?php unset($__attributesOriginal5566490223217002dd49001ada8a35db); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5566490223217002dd49001ada8a35db)): ?>
<?php $component = $__componentOriginal5566490223217002dd49001ada8a35db; ?>
<?php unset($__componentOriginal5566490223217002dd49001ada8a35db); ?>
<?php endif; ?> <?php endif; ?>
            <?php if($case->complainant): ?> <?php if (isset($component)) { $__componentOriginal5566490223217002dd49001ada8a35db = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5566490223217002dd49001ada8a35db = $attributes; } ?>
<?php $component = App\View\Components\PersonShowCard::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('person-show-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\PersonShowCard::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['person' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($case->complainant),'label' => 'Complainant Details (Read-only)']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5566490223217002dd49001ada8a35db)): ?>
<?php $attributes = $__attributesOriginal5566490223217002dd49001ada8a35db; ?>
<?php unset($__attributesOriginal5566490223217002dd49001ada8a35db); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5566490223217002dd49001ada8a35db)): ?>
<?php $component = $__componentOriginal5566490223217002dd49001ada8a35db; ?>
<?php unset($__componentOriginal5566490223217002dd49001ada8a35db); ?>
<?php endif; ?> <?php endif; ?>
        </div>

         <!-- Right Column: Other Actions (Each in its own form) -->
    <div class="space-y-8">
        <!-- Upload Media Card -->
        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md">
            <h3 class="text-xl font-semibold mb-4 text-gray-800 dark:text-gray-200">Upload Media</h3>
            <form action="<?php echo e(route('photographer.case.media.store', $case)); ?>" method="POST" enctype="multipart/form-data" class="space-y-4">
                <?php echo csrf_field(); ?>
                <div><label for="media_file" class="block font-semibold text-sm text-gray-700 dark:text-gray-300 mb-1">Media File</label><input type="file" name="media_file" id="media_file" class="form-input" required></div>
                <div><label for="type" class="block font-semibold text-sm text-gray-700 dark:text-gray-300 mb-1">Media Type</label><select name="type" id="type" class="form-select" required><option value="image">Image</option><option value="video">Video</option></select></div>
                <div><label for="description" class="block font-semibold text-sm text-gray-700 dark:text-gray-300 mb-1">Description</label><input type="text" name="description" id="description" class="form-input" placeholder="e.g. Exhibit A..." required></div>
                <button type="submit" class="w-full bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 font-semibold flex items-center justify-center gap-2"><i data-feather="upload" class="w-4 h-4"></i> Upload</button>
            </form>
        </div>
        
        <!-- Finalize Card -->
        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md">
            <h3 class="text-xl font-semibold mb-4 text-gray-800 dark:text-gray-200">Finalize Case</h3>
            <form action="<?php echo e(route('photographer.case.finalize', $case)); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PUT'); ?>
                <?php if(in_array($case->case_type, ['Murder', 'Sudden Death'])): ?>
                    <div class="mb-4"><label for="finalize_cause_of_death" class="block font-semibold text-gray-700 dark:text-gray-300 mb-1">Cause of Death <span class="text-red-500">*</span></label><input type="text" id="finalize_cause_of_death" name="cause_of_death" value="<?php echo e(old('cause_of_death', $case->cause_of_death)); ?>" class="form-input p-2" placeholder="Required to finalize" required></div>
                    <button type="submit" class="w-full bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700 font-semibold">Finalize with Cause of Death</button>
                <?php else: ?>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">This will lock the case from further uploads.</p>
                    <button type="submit" class="w-full bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700 font-semibold">Finalize Case</button>
                <?php endif; ?>
            </form>
        </div>
    </div>
</div> <!-- End of Grid -->>

    <!-- Media Gallery -->
    <div class="mt-8 bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md">
        <h3 class="text-xl font-semibold mb-6 border-b dark:border-gray-700 pb-2 text-gray-800 dark:text-gray-200">Uploaded Media</h3>
        <?php if($case->media->isEmpty()): ?>
            <p class="text-gray-500 dark:text-gray-400 italic">No media has been uploaded for this case.</p>
        <?php else: ?>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                <?php $__currentLoopData = $case->media; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $media): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="border dark:border-gray-700 rounded-lg shadow-sm overflow-hidden bg-gray-50 dark:bg-gray-700/50">
                        <div class="p-3">
                            <?php if($media->type === 'image'): ?>
                                <a href="<?php echo e(asset('storage/' . $media->path)); ?>" target="_blank"><img src="<?php echo e(asset('storage/' . $media->path)); ?>" alt="Case Image" class="w-full h-48 object-cover rounded-md mb-3"></a>
                            <?php elseif($media->type === 'video'): ?>
                                <video controls class="w-full h-48 rounded-md mb-3"><source src="<?php echo e(asset('storage/' . $media->path)); ?>" type="video/mp4"></video>
                            <?php endif; ?>
                            <p class="text-sm text-gray-700 dark:text-gray-300 italic mb-3"><?php echo e($media->description); ?></p>
                            <div class="flex justify-end pt-2 border-t dark:border-gray-600">
                                <form action="<?php echo e(route('photographer.media.destroy', $media)); ?>" method="POST" onsubmit="deleteConfirmation(event, this)">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="text-red-500 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300 text-sm font-semibold">Delete</button>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>

<script>
    function deleteConfirmation(event, form) {
        event.preventDefault();
        Swal.fire({
            title: 'Are you sure?',
            text: "This media file will be permanently deleted!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => { if (result.isConfirmed) { form.submit(); } })
    }
</script>

<style>
    .form-input, .form-select, .form-textarea { padding: 0.5rem 0.75rem; width: 100%; border-radius: 0.375rem; border: 1px solid #D1D5DB; box-shadow: 0 1px 2px 0 rgb(0 0 0 / 0.05); }
    .dark .form-input, .dark .form-select, .dark .form-textarea { background-color: #374151; border-color: #4B5563; color: #D1D5DB; }
</style>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.photographer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\inetpub\wwwroot\myproject\resources\views/photographer/case-details.blade.php ENDPATH**/ ?>