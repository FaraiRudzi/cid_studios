

<?php $__env->startSection('title', 'Photographer Details'); ?>

<?php $__env->startSection('content'); ?>
<div class="container mx-auto">

    
    <div class="max-w-4xl mx-auto mb-6 flex justify-between items-center">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-200">
            Photographer Details
        </h1>
        
        <a href="<?php echo e(route('admin.photographers.index')); ?>" class="text-blue-600 dark:text-blue-400 hover:underline flex items-center gap-2">
            <i data-feather="arrow-left" class="w-5 h-5"></i>
            Back to List
        </a>
    </div>

    
    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md max-w-4xl mx-auto">
        
        
        <dl class="grid grid-cols-[auto,1fr] gap-x-6 gap-y-3 text-base">
            
            <dt class="font-semibold text-gray-600 dark:text-gray-400">Full Name:</dt>
            <dd class="text-gray-900 dark:text-gray-100"><?php echo e($photographer->first_name); ?> <?php echo e($photographer->surname); ?></dd>
            
            <dt class="font-semibold text-gray-600 dark:text-gray-400">Force Number:</dt>
            <dd class="text-gray-900 dark:text-gray-100"><?php echo e($photographer->force_number); ?></dd>
            
            <dt class="font-semibold text-gray-600 dark:text-gray-400">Username:</dt>
            <dd class="text-gray-900 dark:text-gray-100"><?php echo e($photographer->username); ?></dd>
            
            <dt class="font-semibold text-gray-600 dark:text-gray-400">Email Address:</dt>
            <dd class="text-gray-900 dark:text-gray-100"><?php echo e($photographer->email); ?></dd>
            
            <dt class="font-semibold text-gray-600 dark:text-gray-400">Phone Number:</dt>
            <dd class="text-gray-900 dark:text-gray-100"><?php echo e($photographer->phone_number); ?></dd>
            
            <dt class="font-semibold text-gray-600 dark:text-gray-400">Joined On:</dt>
            <dd class="text-gray-900 dark:text-gray-100">
                <?php echo e($photographer->created_at ? $photographer->created_at->format('d M Y, H:i A') : 'N/A'); ?>

            </dd>
            
        </dl>
        
        <hr class="my-6 border-gray-200 dark:border-gray-700">

        
        <div class="flex items-center gap-4">
            <a href="<?php echo e(route('admin.photographers.edit', $photographer->id)); ?>" class="bg-yellow-500 text-white px-4 py-2 rounded-md hover:bg-yellow-600 font-semibold flex items-center gap-2">
                <i data-feather="edit" class="w-5 h-5"></i>
                Edit Photographer
            </a>
            
            <form method="POST" action="<?php echo e(route('admin.photographers.destroy', $photographer->id)); ?>" onsubmit="deleteConfirmation(event, this)">
                <?php echo csrf_field(); ?>
                <?php echo method_field('DELETE'); ?>
                <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded-md hover:bg-red-600 font-semibold flex items-center gap-2">
                    <i data-feather="trash-2" class="w-5 h-5"></i>
                    Delete Photographer
                </button>
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
            text: "You won't be able to revert this!",
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
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\inetpub\wwwroot\myproject\resources\views/admin/photographers/show.blade.php ENDPATH**/ ?>