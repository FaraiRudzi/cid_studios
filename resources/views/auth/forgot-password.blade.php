<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Forgot Password - CID Studios</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

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
    
            {{-- Title Section (No Logo) --}}
            <div class="text-center mb-8">
                <h2 class="text-2xl font-bold text-gray-900">
                    Forgot Your Password?
                </h2>
                <p class="mt-2 text-sm text-gray-600">
                    No problem. Just let us know your email address and we will email you a password reset link.
                </p>
            </div>

            <form method="POST" action="{{ route('password.email') }}">
                @csrf

                <!-- Email Address -->
                <div class="mb-6">
                    <label for="email" class="block font-semibold text-sm text-gray-700 mb-1">Email</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                           class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>

                <!-- Submit Button -->
                <div class="flex items-center justify-between">
                    <button type="submit"
                            class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition ease-in-out duration-150">
                        Email Password Reset Link
                    </button>
                </div>
            </form>

            {{-- Link back to Login Page --}}
            <p class="mt-8 text-sm text-center text-gray-600">
                Remembered your password?
                <a href="{{ route('login') }}" class="font-medium text-blue-600 hover:underline">
                    Sign In
                </a>
            </p>
        </div>
    </div>

    {{-- This script block will now handle BOTH success and error messages --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            @if (session('status')) // Note: Password reset uses 'status' session key
                Swal.fire({
                    icon: 'success',
                    title: 'Link Sent!',
                    text: '{{ session('status') }}',
                });
            @endif

            @if ($errors->any())
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    html: `{!! $errors->first() !!}` // Show only the first error for a clean message
                });
            @endif
        });
    </script>

</body>
</html>