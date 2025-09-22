

<?php $__env->startSection('title', 'Edit Photographer'); ?>

<?php $__env->startSection('content'); ?>
<div class="container mx-auto">

    
    <div class="flex justify-between items-center mb-6 max-w-4xl mx-auto">
        <h1 class="text-2xl font-bold text-gray-800">
            Edit Photographer
        </h1>
        
        <a href="<?php echo e(route('admin.photographers.show', $photographer->id)); ?>" class="text-blue-600 hover:underline flex items-center gap-2">
            <i data-feather="arrow-left" class="w-5 h-5"></i>
            Back to Photographer Details
        </a>
    </div>

    
    <div class="bg-white p-6 rounded-lg shadow-md max-w-4xl mx-auto">
        <form action="<?php echo e(route('admin.photographers.update', $photographer->id)); ?>" method="POST" class="space-y-6">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>

            
            <h3 class="text-lg font-semibold text-gray-800 border-b pb-2">Personal & Contact Information</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="first_name" class="block font-semibold text-gray-700 mb-1">First Name</label>
                    <input type="text" name="first_name" id="first_name" value="<?php echo e(old('first_name', $photographer->first_name)); ?>" required class="w-full border-gray-300 rounded-md shadow-sm">
                </div>
                <div>
                    <label for="surname" class="block font-semibold text-gray-700 mb-1">Surname</label>
                    <input type="text" name="surname" id="surname" value="<?php echo e(old('surname', $photographer->surname)); ?>" required class="w-full border-gray-300 rounded-md shadow-sm">
                </div>
                 <div>
                    <label for="phone_number" class="block font-semibold text-gray-700 mb-1">Phone Number</label>
                    <input type="text" name="phone_number" id="phone_number" value="<?php echo e(old('phone_number', $photographer->phone_number)); ?>" required class="w-full border-gray-300 rounded-md shadow-sm">
                </div>
                <div>
                    <label for="email" class="block font-semibold text-gray-700 mb-1">Email Address</label>
                    <input type="email" name="email" id="email" value="<?php echo e(old('email', $photographer->email)); ?>" required class="w-full border-gray-300 rounded-md shadow-sm">
                </div>
            </div>

            
            <h3 class="text-lg font-semibold text-gray-800 border-b pb-2 pt-4">Account Credentials</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="force_number" class="block font-semibold text-gray-700 mb-1">Force Number</label>
                    <input type="text" name="force_number" id="force_number" value="<?php echo e(old('force_number', $photographer->force_number)); ?>" required class="w-full border-gray-300 rounded-md shadow-sm">
                </div>
                <div>
                    <label for="username" class="block font-semibold text-gray-700 mb-1">Username</label>
                    <input type="text" name="username" id="username" value="<?php echo e(old('username', $photographer->username)); ?>" required class="w-full border-gray-300 rounded-md shadow-sm">
                </div>
                <div>
                    <label for="password" class="block font-semibold text-gray-700 mb-1">New Password</label>
                    <input type="password" name="password" id="password" placeholder="Leave blank to keep current" class="w-full border-gray-300 rounded-md shadow-sm">
                </div>
                <div>
                    <label for="password_confirmation" class="block font-semibold text-gray-700 mb-1">Confirm New Password</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" class="w-full border-gray-300 rounded-md shadow-sm">
                </div>
            </div>

            
            <div class="pt-6 flex items-center gap-4 border-t mt-6">
                <button type="submit" class="bg-blue-600 text-white px-5 py-2 rounded-md hover:bg-blue-700 font-semibold flex items-center gap-2">
                    <i data-feather="save" class="w-5 h-5"></i>
                    Update Photographer
                </button>
                <a href="<?php echo e(route('admin.photographers.show', $photographer->id)); ?>" class="text-gray-600 hover:underline">Cancel</a>
            </div>
        </form>
    </div>
</div>


<?php if($errors->any()): ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                icon: 'error',
                title: 'Validation Error',
                html: `<?php echo implode('<br>', $errors->all()); ?>`
            });
        });
    </script>
<?php endif; ?>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\inetpub\wwwroot\myproject\resources\views/admin/photographers/edit.blade.php ENDPATH**/ ?>