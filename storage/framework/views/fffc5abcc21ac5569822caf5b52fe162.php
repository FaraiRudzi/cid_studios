<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login - CID Studios</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="apple-touch-icon" sizes="180x180" href="<?php echo e(asset('apple-touch-icon.png')); ?>">
<link rel="icon" type="image/png" sizes="32x32" href="<?php echo e(asset('favicon-96x96.png')); ?>">
<link rel="icon" type="image/png" sizes="16x16" href="<?php echo e(asset('favicon-96x96.png')); ?>">
<link rel="manifest" href="<?php echo e(asset('site.webmanifest')); ?>">
<link rel="shortcut icon" href="<?php echo e(asset('favicon.ico')); ?>">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Feather Icons -->
    <script src="https://unpkg.com/feather-icons"></script>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-gray-100 font-sans text-gray-800">

    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0">
        
        <div class="w-full sm:max-w-md mt-6 px-6 py-8 bg-white shadow-md overflow-hidden sm:rounded-lg">
    
            
            
<div class="text-center mb-8">
    
    <img src="<?php echo e(asset('images/logo.jpg')); ?>" alt="CID Studios Logo" class="mx-auto h-16 w-auto">
    
    <h2 class="mt-4 text-2xl font-bold text-gray-900">
        CID Studios
    </h2>
</div>

            <form method="POST" action="<?php echo e(route('login')); ?>">
                <?php echo csrf_field(); ?>

                <!-- Email Address -->
                <div class="mb-4">
                    <label for="email" class="block font-semibold text-sm text-gray-700 mb-1">Email</label>
                    <input id="email" type="email" name="email" value="<?php echo e(old('email')); ?>" required autofocus
                           class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>

                <!-- Password -->
                <div class="mb-4">
                    <label for="password" class="block font-semibold text-sm text-gray-700 mb-1">Password</label>
                    <input id="password" type="password" name="password" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>

                <!-- User Type -->
                <div class="mb-4">
                    <label for="user_type" class="block font-semibold text-sm text-gray-700 mb-1">User Type</label>
                    <select name="user_type" id="user_type" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="admin">Administrator</option>
                        <option value="photographer">Photographer</option>
                    </select>
                </div>

                <!-- Remember Me & Forgot Password -->
                <div class="flex items-center justify-between mb-6">
                    <label class="flex items-center text-sm">
                        <input type="checkbox" name="remember" class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500">
                        <span class="ml-2 text-gray-600">Remember me</span>
                    </label>

                    <a href="<?php echo e(route('password.request')); ?>" class="text-sm text-blue-600 hover:underline">
                        Forgot your password?
                    </a>
                </div>

                <!-- Login Button -->
                <div class="flex items-center">
                    <button type="submit"
                            class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition ease-in-out duration-150">
                        Sign In
                    </button>
                </div>
            </form>

            
            <p class="mt-8 text-sm text-center text-gray-600">
                Not a member?
                <a href="<?php echo e(route('register')); ?>" class="font-medium text-blue-600 hover:underline">
                    Register here
                </a>
            </p>
        </div>
    </div>

    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            <?php if(session('success')): ?>
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: '<?php echo e(session('success')); ?>',
                    timer: 3000,
                    showConfirmButton: false
                });
            <?php endif; ?>

            <?php if($errors->any()): ?>
                Swal.fire({
                    icon: 'error',
                    title: 'Login Failed',
                    html: `<?php echo $errors->first(); ?>` // Use the first error for a cleaner message
                });
            <?php endif; ?>
        });
    </script>

</body>
</html><?php /**PATH C:\inetpub\wwwroot\myproject\resources\views/auth/login.blade.php ENDPATH**/ ?>