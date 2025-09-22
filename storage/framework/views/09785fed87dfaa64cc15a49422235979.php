

<?php $__env->startSection('title', 'Add New Station'); ?>

<?php $__env->startSection('content'); ?>
<div class="container mx-auto">

    
    <div class="max-w-xl mx-auto mb-6 flex justify-between items-center">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-200">
            Add New Station
        </h1>
        
        <a href="<?php echo e(route('admin.stations.index')); ?>" class="text-blue-600 dark:text-blue-400 hover:underline flex items-center gap-2">
            <i data-feather="arrow-left" class="w-5 h-5"></i>
            Back to Station List
        </a>
    </div>

    
    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md max-w-xl mx-auto">
        <form action="<?php echo e(route('admin.stations.store')); ?>" method="POST">
            <?php echo csrf_field(); ?>

            
            <div>
                <label for="name" class="block font-semibold text-gray-700 dark:text-gray-300 mb-1">Station Name</label>
                <input type="text" 
                       name="name" 
                       id="name" 
                       value="<?php echo e(old('name')); ?>" 
                       class="w-full border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300 rounded-md shadow-sm" 
                       required 
                       autofocus>
            </div>

            
            <div class="pt-6 flex items-center gap-4 border-t dark:border-gray-700 mt-6">
                <button type="submit" class="bg-blue-600 text-white px-5 py-2 rounded-md hover:bg-blue-700 font-semibold flex items-center gap-2">
                    <i data-feather="save" class="w-5 h-5"></i>
                    Save Station
                </button>
                <a href="<?php echo e(route('admin.stations.index')); ?>" class="text-gray-600 dark:text-gray-400 hover:underline">Cancel</a>
            </div>
        </form>
    </div>
</div>


<?php $__env->startPush('scripts'); ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        <?php if($errors->any()): ?>
            Swal.fire({
                icon: 'error',
                title: 'Validation Error',
                html: `<?php echo $errors->first('name'); ?>` // Show only the first error for a clean message
            });
        <?php endif; ?>
    });
</script>
<?php $__env->stopPush(); ?>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\inetpub\wwwroot\myproject\resources\views/admin/stations/create.blade.php ENDPATH**/ ?>