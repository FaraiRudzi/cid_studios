

<?php $__env->startSection('title', 'Station Details'); ?>

<?php $__env->startSection('content'); ?>
<div class="container mx-auto">

    
    <div class="max-w-2xl mx-auto mb-6 flex justify-between items-center">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-200">
            Station Details
        </h1>
        
        <a href="<?php echo e(route('admin.stations.index')); ?>" class="text-blue-600 dark:text-blue-400 hover:underline flex items-center gap-2">
            <i data-feather="arrow-left" class="w-5 h-5"></i>
            Back to List
        </a>
    </div>

    
    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md max-w-2xl mx-auto">
        
        <dl class="grid grid-cols-[auto,1fr] gap-x-4 gap-y-2 text-base">
            <dt class="font-semibold text-gray-600 dark:text-gray-400">Station Name:</dt>
            <dd class="text-gray-900 dark:text-gray-100"><?php echo e($station->name); ?></dd>

            <dt class="font-semibold text-gray-600 dark:text-gray-400">Created At:</dt>
            <dd class="text-gray-900 dark:text-gray-100">
                <?php echo e($station->created_at ? $station->created_at->format('d M Y, H:i A') : 'N/A'); ?>

            </dd>
            
            <dt class="font-semibold text-gray-600 dark:text-gray-400">Last Updated:</dt>
            <dd class="text-gray-900 dark:text-gray-100">
                <?php echo e($station->updated_at ? $station->updated_at->format('d M Y, H:i A') : 'N/A'); ?>

            </dd>
        </dl>
        
        <hr class="my-6 border-gray-200 dark:border-gray-700">

        
        <div class="flex items-center gap-4">
            <a href="<?php echo e(route('admin.stations.edit', $station->id)); ?>" class="bg-yellow-500 text-white px-4 py-2 rounded-md hover:bg-yellow-600 font-semibold flex items-center gap-2">
                <i data-feather="edit" class="w-5 h-5"></i>
                Edit
            </a>
            
            
            <form method="POST" action="<?php echo e(route('admin.stations.destroy', $station->id)); ?>" onsubmit="deleteConfirmation(event, this)">
                <?php echo csrf_field(); ?>
                <?php echo method_field('DELETE'); ?>
                <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded-md hover:bg-red-600 font-semibold flex items-center gap-2">
                    <i data-feather="trash-2" class="w-5 h-5"></i>
                    Delete
                </button>
            </form>
        </div>

    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>

<script>
    function deleteConfirmation(event, form) {
        // Prevent the form from submitting immediately
        event.preventDefault();

        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            // If the user confirms, then submit the form
            if (result.isConfirmed) {
                form.submit();
            }
        })
    }
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\inetpub\wwwroot\myproject\resources\views/admin/stations/show.blade.php ENDPATH**/ ?>