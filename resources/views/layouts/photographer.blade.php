<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="layout()" x-bind:class="{ 'dark': isDarkMode }">
<head>
    <meta charset="UTF-8">
    <title>@yield('title') - CID Studios Photographer Portal</title>
    <meta name="viewport" content="width=device-width, initial-scale-1">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/feather-icons"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style> [x-cloak] { display: none !important; } </style>
    <script>
        if (localStorage.getItem('darkMode') === 'true' || 
           (!('darkMode' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else { 
            document.documentElement.classList.remove('dark'); 
        }
    </script>
</head>
<body class="bg-gray-100 dark:bg-gray-900 font-sans text-gray-800 dark:text-gray-200">

<div class="min-h-screen flex flex-col">
    <!-- Navbar -->
    <header class="bg-white dark:bg-gray-800 shadow p-4 flex justify-between items-center h-16 z-10 shrink-0">
        <!-- Left Side: Logo & Main Nav -->
        <div class="flex items-center gap-6">
            <a href="{{ route('photographer.dashboard') }}" class="flex items-center gap-3">
                <img src="{{ asset('images/badge.png') }}" alt="Logo" class="h-8 w-8">
                <h1 class="text-xl font-bold hidden sm:block">Photographer Portal</h1>
            </a>
            
            <!-- Persistent Navigation -->
            <nav class="hidden md:flex items-center gap-4">
                <a href="{{ route('photographer.dashboard') }}" 
                   class="nav-link-photographer {{ request()->routeIs('photographer.dashboard') ? 'active' : '' }}">
                    Dashboard
                </a>
                {{-- You can add more links here in the future --}}
            </nav>
        </div>
        
        <!-- Right Side: Theme Toggle & User Menu -->
        <div class="flex items-center gap-4">
            <button @click="toggleTheme()" class="p-2 rounded-full hover:bg-gray-200 dark:hover:bg-gray-700">
                <i data-feather="sun" x-show="!isDarkMode" x-cloak></i>
                <i data-feather="moon" x-show="isDarkMode" x-cloak></i>
            </button>
            <div x-data="{ open: false }" class="relative">
                <button @click="open = !open" class="flex items-center gap-2">
                    <span class="hidden sm:inline">@auth('photographer') {{ Auth::user()->first_name }} @endauth</span>
                    <i data-feather="chevron-down" class="w-4 h-4"></i>
                </button>
                <div x-show="open" @click.away="open = false" x-cloak class="absolute right-0 mt-2 w-48 bg-white dark:bg-gray-700 rounded-md shadow-lg py-1 z-50">
                    <a href="#" class="block w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-600">My Profile</a>
                    <div class="border-t border-gray-100 dark:border-gray-600"></div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-500 hover:bg-gray-100 dark:hover:bg-gray-600">Logout</button>
                    </form>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="flex-grow p-6 lg:p-8">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-white dark:bg-gray-800 p-4 text-center text-sm text-gray-500 dark:text-gray-400 shrink-0 border-t dark:border-gray-700">
        © {{ date('Y') }} Police Software Development Team. All rights reserved.
    </footer>
</div>

{{-- Custom styles for the photographer nav link --}}
<style>
    .nav-link-photographer {
        padding: 0.5rem 1rem;
        border-radius: 0.375rem;
        font-weight: 500;
        color: #4B5563; /* text-gray-600 */
        transition: background-color 0.2s, color 0.2s;
    }
    .dark .nav-link-photographer { color: #D1D5DB; } /* text-gray-300 */
    .nav-link-photographer:hover { color: #1F2937; background-color: #F3F4F6; } /* hover:text-gray-800 hover:bg-gray-100 */
    .dark .nav-link-photographer:hover { color: #F9FAFB; background-color: #374151; } /* dark:hover:text-gray-50 dark:hover:bg-gray-700 */
    .nav-link-photographer.active {
        color: #1D4ED8; /* text-blue-700 */
        font-weight: 600;
    }
    .dark .nav-link-photographer.active { color: #60A5FA; } /* dark:text-blue-400 */
</style>

<script>
    function layout() {
        return {
            isDarkMode: localStorage.getItem('darkMode') === 'true',
            toggleTheme() { this.isDarkMode = !this.isDarkMode; localStorage.setItem('darkMode', this.isDarkMode); },
            init() { 
                feather.replace();
                // Comprehensive notifications
                @if(session('success'))
                    Swal.fire({ icon: 'success', title: 'Success!', text: '{{ session('success') }}', timer: 3000, showConfirmButton: false });
                @endif
                @if(session('error'))
                    Swal.fire({ icon: 'error', title: 'Error!', text: '{{ session('error') }}' });
                @endif
                @if($errors->any())
                    Swal.fire({ icon: 'error', title: 'Validation Error', html: `{!! implode('<br>', $errors->all()) !!}` });
                @endif
            }
        }
    }
    document.addEventListener('alpine:init', () => { Alpine.data('layout', layout); });
</script>

@stack('scripts')
</body>
</html>