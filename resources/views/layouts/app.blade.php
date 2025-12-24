<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="layout()" :class="{ 'dark': isDarkMode }">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'CID Studio Admin')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/feather-icons"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

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

    <style>
        [x-cloak] { display: none !important; }
        .sidebar-transition { transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
        .nav-link-active {
            background-color: rgba(255, 215, 0, 0.1);
            border-left: 4px solid #FFD700;
            color: #FFD700 !important;
        }
        .no-scrollbar::-webkit-scrollbar { display: none; }



    </style>
</head>
<body class="bg-gray-50 dark:bg-dark-bg text-gray-800 dark:text-gray-200">

<div class="flex h-screen overflow-hidden">

    <aside
        class="sidebar-transition bg-zrp-blue flex flex-col h-full z-50 shadow-2xl relative"
        :class="isSidebarCollapsed ? 'w-20' : 'w-72'"
    >
        <div class="h-20 flex items-center border-b border-white/10 overflow-hidden transition-all duration-300"
             :class="isSidebarCollapsed ? 'justify-center px-0' : 'px-6'">
            <div class="flex items-center gap-4 shrink-0">
                <div class="p-1.5 bg-white rounded-lg shrink-0 shadow-inner">
                    <img src="{{ asset('images/logo.jpg') }}" alt="ZRP" class="h-8 w-8 object-contain">
                </div>
                <span x-show="!isSidebarCollapsed" x-cloak
                      class="font-black text-white tracking-tighter text-xl whitespace-nowrap">
                      CID STUDIOS
                </span>
            </div>
        </div>

        <nav class="flex-1 px-3 mt-8 space-y-1.5 overflow-y-auto no-scrollbar">
    @php
        // Ensure these names match your 'name()' methods in routes/web.php exactly
        $menuItems = [
            ['route' => 'admin.dashboard', 'icon' => 'grid', 'label' => 'Dashboard', 'pattern' => 'admin.dashboard'],
            ['route' => 'admin.stations.index', 'icon' => 'map-pin', 'label' => 'Stations', 'pattern' => 'admin.stations.*'],
            ['route' => 'admin.cases.index', 'icon' => 'folder', 'label' => 'Case Files', 'pattern' => 'admin.cases.*'],
            ['route' => 'admin.photographers.index', 'icon' => 'camera', 'label' => 'Photographers', 'pattern' => 'admin.photographers.*'],
        ];
    @endphp

    @foreach($menuItems as $item)
        @php
            // Safety check: if the route doesn't exist, use '#' to prevent the crash
            $href = Route::has($item['route']) ? route($item['route']) : '#';
            $isActive = request()->routeIs($item['pattern']);
        @endphp

        <a href="{{ $href }}"
           class="flex items-center rounded-xl transition-all group py-3.5 relative overflow-hidden {{ $isActive ? 'nav-link-active' : 'text-gray-400 hover:bg-white/10 hover:text-white' }}"
           :class="isSidebarCollapsed ? 'justify-center px-0' : 'px-4 gap-4'">

            <div class="w-6 flex justify-center shrink-0">
                <i data-feather="{{ $item['icon'] }}"
                   class="w-5 h-5 transition-transform group-hover:scale-110 {{ $isActive ? 'text-zrp-gold' : '' }}">
                </i>
            </div>

            <span x-show="!isSidebarCollapsed"
                  x-transition:enter="transition ease-out duration-200"
                  x-transition:enter-start="opacity-0 -translate-x-4"
                  x-transition:enter-end="opacity-100 translate-x-0"
                  x-cloak
                  class="font-semibold whitespace-nowrap">
                  {{ $item['label'] }}
            </span>

            <div x-show="isSidebarCollapsed" class="sr-only">{{ $item['label'] }}</div>
        </a>
    @endforeach
</nav>

        <div class="p-4 border-t border-white/10 flex justify-center bg-zrp-blue/50">
            <button @click="isSidebarCollapsed = !isSidebarCollapsed"
                    type="button"
                    class="flex items-center justify-center h-11 w-11 rounded-full border-2 border-zrp-gold text-zrp-gold hover:bg-zrp-gold hover:text-zrp-blue transition-all duration-300 shadow-[0_0_15px_rgba(255,215,0,0.2)]">

                <i x-show="!isSidebarCollapsed" data-feather="chevrons-left" class="w-6 h-6 stroke-[3]"></i>
                <i x-show="isSidebarCollapsed" data-feather="chevrons-right" class="w-6 h-6 stroke-[3]"></i>
            </button>
        </div>
    </aside>

    <div class="flex-1 flex flex-col overflow-hidden">
        <header class="h-20 bg-white dark:bg-gray-800 shadow-sm flex items-center justify-between px-8 border-b dark:border-gray-700">
            <h2 class="text-xl font-bold text-zrp-blue dark:text-zrp-gold tracking-tight">@yield('title')</h2>

            <div class="flex items-center gap-6">
                <div class="flex items-center gap-4 border-r pr-6 dark:border-gray-700">
                    <button @click="toggleTheme()" class="p-2 rounded-lg text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                        <i data-feather="sun" x-show="!isDarkMode" class="w-5 h-5"></i>
                        <i data-feather="moon" x-show="isDarkMode" class="w-5 h-5"></i>
                    </button>

                    <a href="#" class="flex items-center gap-2 text-sm font-bold text-gray-500 hover:text-zrp-blue dark:hover:text-zrp-gold transition-colors">
                        <i data-feather="user" class="w-5 h-5"></i>
                        <span class="hidden lg:inline">My Profile</span>
                    </a>
                </div>

                <div x-data="{ open: false }" class="relative">
                    <button @click="open = !open" class="flex items-center gap-3 focus:outline-none group">
                        <div class="h-10 w-10 rounded-full bg-zrp-blue text-zrp-gold flex items-center justify-center font-black border-2 border-zrp-gold shadow-md group-hover:scale-105 transition-transform">
                            {{ substr(Auth::user()->name, 0, 1) }}
                        </div>
                        <div class="text-left hidden sm:block">
                            <p class="text-xs font-black text-zrp-blue dark:text-white leading-none uppercase">{{ Auth::user()->name }}</p>
                            <p class="text-[10px] text-gray-400 font-bold mt-1">SUPER ADMIN</p>
                        </div>
                    </button>
                    <div x-show="open" @click.away="open = false" x-transition x-cloak
                         class="absolute right-0 mt-3 w-48 bg-white dark:bg-gray-800 rounded-2xl shadow-2xl py-2 border dark:border-gray-700 z-[60]">
                         <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full text-left px-4 py-3 text-sm text-red-500 font-bold hover:bg-red-50 dark:hover:bg-red-900/20 flex items-center gap-3">
                                <i data-feather="log-out" class="w-4 h-4"></i> Sign Out
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </header>

        <main class="flex-1 overflow-y-auto p-8 bg-gray-50 dark:bg-dark-bg">
            @yield('content')
        </main>
    </div>
</div>
 <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function layout() {
        return {
            isSidebarCollapsed: localStorage.getItem('sidebarCollapsed') === 'true',
            isDarkMode: localStorage.getItem('darkMode') === 'true',
            toggleTheme() {
                this.isDarkMode = !this.isDarkMode;
                localStorage.setItem('darkMode', this.isDarkMode);
                document.documentElement.classList.toggle('dark', this.isDarkMode);
            },
            init() {
                this.$watch('isSidebarCollapsed', val => {
                    localStorage.setItem('sidebarCollapsed', val);
                    // Re-initialize icons after layout shift
                    setTimeout(() => feather.replace(), 50);
                });
                if (this.isDarkMode) document.documentElement.classList.add('dark');
                feather.replace();
            }
        }
    }


    document.addEventListener('DOMContentLoaded', function() {
        @if(session('success'))
            Swal.fire({
                title: 'SUCCESS!',
                text: "{{ session('success') }}",
                icon: 'success',
                confirmButtonColor: '#00264d', // ZRP Blue
                iconColor: '#d4af37',       // ZRP Gold
                background: '#ffffff',
                timer: 3000,
                timerProgressBar: true,
                showConfirmButton: false,
                customClass: {
                    popup: 'rounded-[2rem]',
                    title: 'font-black uppercase tracking-widest text-sm',
                    htmlContainer: 'font-bold text-gray-600'
                }
            });
        @endif

        @if(session('error'))
    Swal.fire({
        title: 'RESTRICTED ACCESS',
        text: "{{ session('error') }}",
        icon: 'error',
        confirmButtonColor: '#00264d'
    });
@endif

@if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'CASE UPDATED',
            text: "{{ session('success') }}",
            timer: 2500,
            showConfirmButton: false,
            background: '#ffffff',
            iconColor: '#d4af37', // ZRP Gold
            customClass: {
                title: 'font-bold text-zrp-blue'
            }
        });
    @endif
    });

</script>
</body>
</html>
