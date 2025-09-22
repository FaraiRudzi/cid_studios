

<?php $__env->startSection('title', 'Case Details'); ?>

<?php $__env->startSection('content'); ?>

<div class="container mx-auto" x-data="{ isExportModalOpen: false }">

    
    <div class="flex flex-col sm:flex-row justify-between sm:items-center gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-200">Case Details</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400"><?php echo e($case->scene_reference_number); ?></p>
        </div>
        <a href="<?php echo e(route('admin.cases.index')); ?>" class="text-blue-600 dark:text-blue-400 hover:underline flex items-center gap-2">
            <i data-feather="arrow-left" class="w-5 h-5"></i> Back to Case List
        </a>
    </div>

    <!-- Grid for main details -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        
        <!-- Card 1: Core Information -->
        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md">
            <h3 class="text-xl font-semibold mb-4 border-b dark:border-gray-700 pb-2 text-gray-800 dark:text-gray-200">Core Information</h3>
            <dl class="grid grid-cols-[auto,1fr] gap-x-6 gap-y-3 text-base">
                <dt class="font-semibold text-gray-600 dark:text-gray-400">Case Reference:</dt>
                <dd class="text-gray-900 dark:text-gray-100"><?php echo e($case->reference_number); ?></dd>
                <dt class="font-semibold text-gray-600 dark:text-gray-400">Station:</dt>
                <dd class="text-gray-900 dark:text-gray-100"><?php echo e($case->station?->name ?? 'N/A'); ?></dd>
                <dt class="font-semibold text-gray-600 dark:text-gray-400">Case Type:</dt>
                <dd class="text-gray-900 dark:text-gray-100"><?php echo e($case->case_type); ?></dd>
                <dt class="font-semibold text-gray-600 dark:text-gray-400">Cause of Death:</dt>
                <dd class="text-gray-900 dark:text-gray-100"><?php echo e($case->cause_of_death ?? 'Not specified'); ?></dd>
            </dl>
        </div>

        <!-- Card 2: Assigned Photographer -->
        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md">
             <h3 class="text-xl font-semibold mb-4 border-b dark:border-gray-700 pb-2 text-gray-800 dark:text-gray-200">Assigned Photographer</h3>
             <dl class="grid grid-cols-[auto,1fr] gap-x-6 gap-y-3 text-base">
                <dt class="font-semibold text-gray-600 dark:text-gray-400">Full Name:</dt>
                <dd class="text-gray-900 dark:text-gray-100"><?php echo e($case->photographer?->first_name ?? 'N/A'); ?> <?php echo e($case->photographer?->surname ?? 'N/A'); ?></dd>
                <dt class="font-semibold text-gray-600 dark:text-gray-400">Force Number:</dt>
                <dd class="text-gray-900 dark:text-gray-100"><?php echo e($case->photographer?->force_number ?? 'N/A'); ?></dd>
                <dt class="font-semibold text-gray-600 dark:text-gray-400">Phone Number:</dt>
                <dd class="text-gray-900 dark:text-gray-100"><?php echo e($case->photographer?->phone_number ?? 'N/A'); ?></dd>
            </dl>
        </div>

        
        <?php if($case->informant): ?>
        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md">
            <h3 class="text-xl font-semibold mb-4 border-b dark:border-gray-700 pb-2 text-gray-800 dark:text-gray-200">Informant Details</h3>
            <dl class="grid grid-cols-[auto,1fr] gap-x-6 gap-y-3 text-base">
                <dt class="font-semibold text-gray-600 dark:text-gray-400">Full Name:</dt><dd class="text-gray-900 dark:text-gray-100"><?php echo e($case->informant->fullName); ?></dd>
                <dt class="font-semibold text-gray-600 dark:text-gray-400">ID Number:</dt><dd class="text-gray-900 dark:text-gray-100"><?php echo e($case->informant->id_number ?? 'N/A'); ?></dd>
                <dt class="font-semibold text-gray-600 dark:text-gray-400">Address:</dt><dd class="text-gray-900 dark:text-gray-100"><?php echo e($case->informant->address ?? 'N/A'); ?></dd>
                <dt class="font-semibold text-gray-600 dark:text-gray-400">Phone:</dt><dd class="text-gray-900 dark:text-gray-100"><?php echo e($case->informant->phone_number ?? 'N/A'); ?></dd>
            </dl>
        </div>
        <?php endif; ?>
        

        <div class="lg:col-span-2 bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md">
            <h3 class="text-xl font-semibold mb-4 border-b dark:border-gray-700 pb-2 text-gray-800 dark:text-gray-200">Circumstances</h3>
            <p class="text-gray-800 dark:text-gray-200 whitespace-pre-line"><?php echo e($case->circumstances); ?></p>
        </div>
    </div> <!-- End of grid -->

    <!-- Action Bar -->
    <div class="mt-8 pt-6 border-t dark:border-gray-700 flex flex-wrap items-center gap-4">
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('reassign', $case)): ?>
        <a href="<?php echo e(route('admin.cases.edit', $case->id)); ?>" class="bg-yellow-500 text-white px-4 py-2 rounded-md hover:bg-yellow-600 font-semibold flex items-center gap-2">
            <i data-feather="user-check" class="w-5 h-5"></i> Reassign Photographer
        </a>
        <?php endif; ?>
        
        <button type="button" @click="isExportModalOpen = true" class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700 font-semibold flex items-center gap-2">
            <i data-feather="file-text" class="w-5 h-5"></i> Export PDF Exhibit
        </button>

        <form action="<?php echo e(route('admin.cases.destroy', $case->id)); ?>" method="POST" onsubmit="deleteConfirmation(event, this)">
            <?php echo csrf_field(); ?>
            <?php echo method_field('DELETE'); ?>
            <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded-md hover:bg-red-600 font-semibold flex items-center gap-2">
                <i data-feather="trash-2" class="w-5 h-5"></i> Delete Case
            </button>
        </form>
    </div>

    <!-- Media Gallery -->
    <div class="mt-8 bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md">
        <h3 class="text-xl font-semibold mb-6 border-b dark:border-gray-700 pb-2 text-gray-800 dark:text-gray-200">Attached Media</h3>
        <?php if($case->media->isEmpty()): ?>
            <p class="text-gray-500 dark:text-gray-400 italic">No media files have been uploaded for this case yet.</p>
        <?php else: ?>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                <?php $__currentLoopData = $case->media; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $media): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="border dark:border-gray-700 rounded-lg p-3 shadow-sm text-center bg-gray-50 dark:bg-gray-700/50">
                        <?php if($media->type === 'image'): ?>
                            <a href="<?php echo e(asset('storage/' . $media->path)); ?>" target="_blank"><img src="<?php echo e(asset('storage/' . $media->path)); ?>" alt="Case Image" class="w-full h-40 object-cover rounded-md mb-2 hover:opacity-80 transition"></a>
                        <?php elseif($media->type === 'video'): ?>
                            <video controls class="w-full h-40 rounded-md mb-2"><source src="<?php echo e(asset('storage/' . $media->path)); ?>" type="video/mp4">Your browser does not support the video tag.</video>
                        <?php endif; ?>
                        <p class="text-sm text-gray-600 dark:text-gray-300 truncate" title="<?php echo e($media->description ?? 'No description'); ?>"><?php echo e($media->description ?? 'No description'); ?></p>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        <?php endif; ?>
    </div>
    
    <!-- EXPORT PDF MODAL -->
    <div x-show="isExportModalOpen" x-cloak class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4 transition-opacity duration-300" x-transition:enter="ease-out" x-transition:leave="ease-in">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl p-6 w-full max-w-2xl" @click.away="isExportModalOpen = false">
            <h3 class="text-xl font-bold mb-4 text-gray-800 dark:text-gray-200">Select Media for Exhibit</h3>
            
            <form action="<?php echo e(route('admin.cases.exportPdf', $case->id)); ?>" method="POST" target="_blank">
                <?php echo csrf_field(); ?>
                <?php if($case->media->isEmpty()): ?>
                    <p class="text-gray-500 dark:text-gray-400">There is no media to select for this case.</p>
                <?php else: ?>
                    <div class="max-h-96 overflow-y-auto border dark:border-gray-700 rounded-md p-4 space-y-4">
                        <?php $__currentLoopData = $case->media; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $media): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <label class="flex items-center gap-4 p-2 rounded hover:bg-gray-100 dark:hover:bg-gray-700 cursor-pointer">
                                <input type="checkbox" name="media_ids[]" value="<?php echo e($media->id); ?>" checked class="h-5 w-5 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                <?php if($media->type === 'image'): ?>
                                <img src="<?php echo e(asset('storage/' . $media->path)); ?>" alt="Thumbnail" class="w-20 h-16 object-cover rounded">
                                <?php else: ?>
                                <div class="w-20 h-16 bg-gray-200 dark:bg-gray-700 rounded flex items-center justify-center">
                                    <i data-feather="video" class="w-8 h-8 text-gray-500"></i>
                                </div>
                                <?php endif; ?>
                                <span class="text-gray-700 dark:text-gray-300"><?php echo e($media->description ?? 'No description'); ?></span>
                            </label>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                <?php endif; ?>
                
                <div class="mt-6 flex justify-end gap-4">
                    <button type="button" @click="isExportModalOpen = false" class="bg-gray-200 dark:bg-gray-600 text-gray-700 dark:text-gray-300 px-4 py-2 rounded-md hover:bg-gray-300 dark:hover:bg-gray-500 font-semibold">Cancel</button>
                    <button type="submit" <?php if($case->media->isEmpty()): ?> disabled <?php endif; ?> class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700 font-semibold disabled:bg-gray-400 disabled:cursor-not-allowed">
                        Generate Exhibit
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>

<script>
    function deleteConfirmation(event, form) {
        event.preventDefault();
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this! Deleting a case will also delete all associated media.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        })
    }
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\inetpub\wwwroot\myproject\resources\views/admin/cases/show.blade.php ENDPATH**/ ?>