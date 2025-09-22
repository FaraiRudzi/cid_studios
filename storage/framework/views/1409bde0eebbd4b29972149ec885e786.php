

<?php $__env->startSection('title', 'Add New Photographer'); ?>

<?php $__env->startSection('content'); ?>
<div class="container mx-auto">

    
    <div class="max-w-4xl mx-auto mb-6 flex justify-between items-center">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-200">
            Add New Photographer
        </h1>
        
        <a href="<?php echo e(route('admin.photographers.index')); ?>" class="text-blue-600 dark:text-blue-400 hover:underline flex items-center gap-2">
            <i data-feather="arrow-left" class="w-5 h-5"></i>
            Back to List
        </a>
    </div>

    
    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md max-w-4xl mx-auto">
        <form action="<?php echo e(route('admin.photographers.store')); ?>" method="POST" class="space-y-6">
            <?php echo csrf_field(); ?>

            
            <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 border-b dark:border-gray-700 pb-2">Personal & Contact Information</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="first_name" class="block font-semibold text-gray-700 dark:text-gray-300 mb-1">First Name</label>
                    <input type="text" name="first_name" id="first_name" value="<?php echo e(old('first_name')); ?>" required class="form-input">
                </div>
                <div>
                    <label for="surname" class="block font-semibold text-gray-700 dark:text-gray-300 mb-1">Surname</label>
                    <input type="text" name="surname" id="surname" value="<?php echo e(old('surname')); ?>" required class="form-input">
                </div>
                 
                 <div>
                    <label for="phone_number" class="block font-semibold text-gray-700 dark:text-gray-300 mb-1">Phone Number</label>
                    <input type="text" name="phone_number" id="phone_number" value="<?php echo e(old('phone_number')); ?>" required class="form-input">
                </div>
                
                <div>
                    <label for="email" class="block font-semibold text-gray-700 dark:text-gray-300 mb-1">Email Address</label>
                    <input type="email" name="email" id="email" value="<?php echo e(old('email')); ?>" required class="form-input">
                </div>
            </div>

            
            <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 border-b dark:border-gray-700 pb-2 pt-4">Account Credentials</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="force_number" class="block font-semibold text-gray-700 dark:text-gray-300 mb-1">Force Number</label>
                    <input type="text" name="force_number" id="force_number" value="<?php echo e(old('force_number')); ?>" required class="form-input">
                </div>
                <div>
                    <label for="username" class="block font-semibold text-gray-700 dark:text-gray-300 mb-1">Username</label>
                    <input type="text" name="username" id="username" value="<?php echo e(old('username')); ?>" required class="form-input">
                </div>
                <div>
                    <label for="password" class="block font-semibold text-gray-700 dark:text-gray-300 mb-1">Password</label>
                    <input type="password" name="password" id="password" required class="form-input">
                </div>
                <div>
                    <label for="password_confirmation" class="block font-semibold text-gray-700 dark:text-gray-300 mb-1">Confirm Password</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" required class="form-input">
                </div>
            </div>

            
            <div class="pt-6 flex items-center gap-4 border-t dark:border-gray-700 mt-6">
                <button type="submit" class="bg-blue-600 text-white px-5 py-2 rounded-md hover:bg-blue-700 font-semibold flex items-center gap-2">
                    <i data-feather="save" class="w-5 h-5"></i>
                    Save Photographer
                </button>
                <a href="<?php echo e(route('admin.photographers.index')); ?>" class="text-gray-600 dark:text-gray-400 hover:underline">Cancel</a>
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
                html: `<?php echo implode('<br>', $errors->all()); ?>`
            });
        <?php endif; ?>
    });
</script>


<style>
    .form-input { width: 100%; border-radius: 0.375rem; border: 1px solid #D1D5DB; box-shadow: 0 1px 2px 0 rgb(0 0 0 / 0.05); }
    .dark .form-input { background-color: #374151; border-color: #4B5563; color: #D1D5DB; }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\inetpub\wwwroot\myproject\resources\views/admin/photographers/create.blade.php ENDPATH**/ ?>