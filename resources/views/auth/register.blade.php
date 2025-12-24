<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register - CID Studios</title>
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
    
            {{-- Logo and Title Section --}}
            <div class="text-center mb-8">
                {{-- <img src="{{ asset('images/badge.png') }}" alt="CID Studios Logo" class="mx-auto h-16 w-auto"> --}}
                <h2 class="mt-4 text-2xl font-bold text-gray-900">
                    Create your Account
                </h2>
            </div>

            <form method="POST" action="{{ route('register') }}">
                @csrf

                <!-- Name -->
                <div class="mb-4">
                    <label for="name" class="block font-semibold text-sm text-gray-700 mb-1">Full Name</label>
                    <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus
                           class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>

                <!-- Email Address -->
                <div class="mb-4">
                    <label for="email" class="block font-semibold text-sm text-gray-700 mb-1">Email</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>

                <!-- Password -->
                <div class="mb-4">
                    <label for="password" class="block font-semibold text-sm text-gray-700 mb-1">Password</label>
                    <input id="password" type="password" name="password" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
                
                <!-- Confirm Password -->
                <div class="mb-6">
                    <label for="password_confirmation" class="block font-semibold text-sm text-gray-700 mb-1">Confirm Password</label>
                    <input id="password_confirmation" type="password" name="password_confirmation" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>


                <!-- Register Button -->
                <div class="flex items-center">
                    <button type="submit"
                            class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition ease-in-out duration-150">
                        Register
                    </button>
                </div>
            </form>

            {{-- Link to Login Page --}}
            <p class="mt-8 text-sm text-center text-gray-600">
                Already have an account?
                <a href="{{ route('login') }}" class="font-medium text-blue-600 hover:underline">
                    Sign In
                </a>
            </p>
        </div>
    </div>

    {{-- This script block will now handle BOTH success and error messages --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            @if (session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: '{{ session('success') }}',
                    timer: 3000,
                    showConfirmButton: false
                });
            @endif

            @if ($errors->any())
                Swal.fire({
                    icon: 'error',
                    title: 'Registration Failed',
                    html: `{!! implode('<br>', $errors->all()) !!}`
                });
            @endif
        });
    </script>

</body>
</html>