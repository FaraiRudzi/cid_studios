<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="layout()" :class="{ 'dark': isDarkMode }">
<head>
    <meta charset="UTF-8">
    <title>@yield('title') - CID Photographer</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/feather-icons"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        'zrp-blue': '#002147',
                        'zrp-gold': '#FFD700',
                        'zrp-gold-hover': '#E6C200',
                        'dark-bg': '#0B1120',
                    }
                }
            }
        }
    </script>
    <style> [x-cloak] { display: none !important; } </style>
</head>
<body class="bg-gray-50 dark:bg-dark-bg text-gray-800 dark:text-gray-200">

<div class="min-h-screen flex flex-col">
    <header class="h-20 bg-zrp-blue shadow-lg flex items-center justify-between px-6 lg:px-12 sticky top-0 z-50">
        <div class="flex items-center gap-4">
            <div class="p-1.5 bg-white rounded-lg shadow-sm">
                <img src="{{ asset('images/logo.jpg') }}" alt="ZRP" class="h-8 w-8 object-contain">
            </div>
            <div class="hidden sm:block">
                <h1 class="text-white font-black tracking-tighter text-lg leading-none">CID STUDIOS</h1>
                <p class="text-zrp-gold text-[10px] font-bold uppercase tracking-widest mt-1">Photographer Portal</p>
            </div>
        </div>

        <div class="flex items-center gap-4 md:gap-8">
            <nav class="hidden md:flex items-center gap-2">
                <a href="{{ route('photographer.dashboard') }}"
                   class="px-4 py-2 rounded-xl text-sm font-bold transition-all {{ request()->routeIs('photographer.dashboard') ? 'bg-zrp-gold text-zrp-blue shadow-lg' : 'text-blue-100 hover:bg-white/10' }}">
                   My Assignments
                </a>
            </nav>

            <div class="h-8 w-[1px] bg-white/10 hidden md:block"></div>

            <div x-data="{ open: false }" class="relative">
                <button @click="open = !open" class="flex items-center gap-3 group focus:outline-none">
                    <div class="h-10 w-10 rounded-full bg-white/10 text-zrp-gold flex items-center justify-center font-black border border-white/20 group-hover:border-zrp-gold transition-all">
                        {{ substr(Auth::user()->first_name ?? 'P', 0, 1) }}
                    </div>
                    <i data-feather="chevron-down" class="w-4 h-4 text-blue-200 group-hover:text-zrp-gold transition-transform" :class="open ? 'rotate-180' : ''"></i>
                </button>

                <div x-show="open" @click.away="open = false" x-transition x-cloak
                     class="absolute right-0 mt-3 w-56 bg-white dark:bg-gray-800 rounded-2xl shadow-2xl py-2 border dark:border-gray-700 z-50">
                    <div class="px-4 py-3 border-b dark:border-gray-700">
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">Signed in as</p>
                        <p class="text-sm font-black text-zrp-blue dark:text-white truncate">{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</p>
                    </div>
                    <a href="#" class="flex items-center gap-3 px-4 py-3 text-sm font-bold text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700">
                        <i data-feather="user" class="w-4 h-4"></i> My Profile
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full text-left px-4 py-3 text-sm font-bold text-red-500 hover:bg-red-50 dark:hover:bg-red-900/10 flex items-center gap-3">
                            <i data-feather="log-out" class="w-4 h-4"></i> Sign Out
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </header>

    <main class="flex-grow p-6 lg:p-12">
        @yield('content')
    </main>

    <footer class="py-6 text-center text-xs font-bold text-gray-400 uppercase tracking-widest">
        &copy; {{ date('Y') }} CID Studios &bull; Forensic Photography Division
    </footer>
</div>

<script>
    function layout() {
        return {
            isDarkMode: localStorage.getItem('darkMode') === 'true',
            toggleTheme() { this.isDarkMode = !this.isDarkMode; localStorage.setItem('darkMode', this.isDarkMode); },
            init() {
                feather.replace();
                @if(session('success')) Swal.fire({ icon: 'success', title: 'Success', text: '{{ session('success') }}', confirmButtonColor: '#002147' }); @endif
                @if(session('error')) Swal.fire({ icon: 'error', title: 'Error', text: '{{ session('error') }}' }); @endif
            }
        }
    }
</script>
</body>
</html>
