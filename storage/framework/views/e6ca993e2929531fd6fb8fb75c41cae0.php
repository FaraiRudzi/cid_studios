<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>" x-data="layout()" x-bind:class="{ 'dark': isDarkMode }">
<head>
    <meta charset="UTF-8">
    <title><?php echo $__env->yieldContent('title', 'CID Studio Media Management'); ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
    <script src="https://unpkg.com/feather-icons"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <link rel="apple-touch-icon" sizes="180x180" href="<?php echo e(asset('apple-touch-icon.png')); ?>">
    <link rel="icon" type="image/png" sizes="32x32" href="<?php echo e(asset('favicon-96x96.png')); ?>">
    <link rel="icon" type="image/png" sizes="16x16" href="<?php echo e(asset('favicon-96x96.png')); ?>">
    <link rel="manifest" href="<?php echo e(asset('site.webmanifest')); ?>">
    <link rel="shortcut icon" href="<?php echo e(asset('favicon.ico')); ?>">
    
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    <style> [x-cloak] { display: none !important; } </style>
    <script>
        if (localStorage.getItem('darkMode') === 'true' || 
           (!('darkMode' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>

    
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        'sidebar-gold': 'oklch(97.3% 0.071 103.193)', // Your light theme gold
                        'sidebar-gold-dark': 'oklch(85% 0.09 95)',   // A complementary darker gold for dark theme
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-gray-100 dark:bg-gray-900 font-sans text-gray-800 dark:text-gray-200">

<div class="flex h-screen bg-gray-100 dark:bg-gray-900">

    <!-- Sidebar -->
    <aside id="sidebar" 
           class="fixed z-30 h-full shadow-lg
                  flex flex-col transition-all duration-300
                  md:relative md:translate-x-0" 
           x-bind:class="{ 
               'w-64': !isSidebarCollapsed, 
               'w-20': isSidebarCollapsed,
               'translate-x-0': isSidebarOpen, 
               '-translate-x-full': !isSidebarOpen 
           }">
        
        <!-- Sidebar Header (ALWAYS WHITE) -->
        <div class="p-3 border-b border-gray-200 dark:border-gray-700 shrink-0 bg-white">
            <div x-show="!isSidebarCollapsed" x-cloak>
                 <img src="<?php echo e(asset('images/logo.jpg')); ?>" alt="Logo" class="w-full h-auto max-h-24 object-contain">
            </div>
            <div x-show="isSidebarCollapsed" x-cloak>
                 <img src="<?php echo e(asset('images/logo.jpg')); ?>" alt="Logo" class="w-full h-auto p-1">
            </div>
            <button class="md:hidden absolute top-4 right-4 text-gray-600" @click="isSidebarOpen = false">
                <i data-feather="x" class="w-5 h-5"></i>
            </button>
        </div>

        <!-- Sidebar Body (Golden Theme) -->
        <div class="flex-grow flex flex-col bg-sidebar-gold dark:bg-sidebar-gold-dark">
            <nav class="flex-grow mt-4 space-y-2 px-3 overflow-y-auto">
                <a href="<?php echo e(route('admin.dashboard')); ?>" class="nav-link" x-bind:class="{ 'active': <?php echo e(request()->routeIs('admin.dashboard') ? 'true' : 'false'); ?> }">
                    <i data-feather="home"></i><span x-show="!isSidebarCollapsed" x-cloak>Dashboard</span>
                </a>
                <a href="<?php echo e(route('admin.stations.index')); ?>" class="nav-link" x-bind:class="{ 'active': <?php echo e(request()->routeIs('admin.stations.*') ? 'true' : 'false'); ?> }">
                    <i data-feather="map-pin"></i><span x-show="!isSidebarCollapsed" x-cloak>Stations</span>
                </a>
                <a href="<?php echo e(route('admin.cases.index')); ?>" class="nav-link" x-bind:class="{ 'active': <?php echo e(request()->routeIs('admin.cases.*') ? 'true' : 'false'); ?> }">
                    <i data-feather="folder"></i><span x-show="!isSidebarCollapsed" x-cloak>Cases</span>
                </a>
                <a href="<?php echo e(route('admin.photographers.index')); ?>" class="nav-link" x-bind:class="{ 'active': <?php echo e(request()->routeIs('admin.photographers.*') ? 'true' : 'false'); ?> }">
                    <i data-feather="camera"></i><span x-show="!isSidebarCollapsed" x-cloak>Photographers</span>
                </a>
            </nav>

            <div class="px-3 py-4 border-t border-black/10 dark:border-white/10 shrink-0">
                <button @click="isSidebarCollapsed = !isSidebarCollapsed" class="nav-link w-full hidden md:flex">
                    <i x-show="!isSidebarCollapsed" data-feather="chevrons-left" x-cloak></i>
                    <i x-show="isSidebarCollapsed" data-feather="chevrons-right" x-cloak></i>
                    <span x-show="!isSidebarCollapsed" x-cloak>Collapse</span>
                </button>
            </div>
        </div>
    </aside>

    <!-- Main content area wrapper -->
    <div class="flex-1 flex flex-col overflow-hidden">
        
        <!-- Navbar -->
        <header class="bg-white dark:bg-gray-800 shadow p-4 flex justify-between items-center h-16 z-10 shrink-0">
            <div class="flex items-center gap-4">
                <button class="md:hidden" @click.stop="isSidebarOpen = !isSidebarOpen"><i data-feather="menu" class="w-6 h-6"></i></button>
                <h1 class="text-xl font-bold"><?php echo $__env->yieldContent('title', 'Dashboard'); ?></h1>
            </div>
            
            <div class="flex items-center gap-4">
                <button @click="toggleTheme()" class="p-2 rounded-full hover:bg-gray-200 dark:hover:bg-gray-700">
                    <i data-feather="sun" x-show="!isDarkMode" x-cloak></i>
                    <i data-feather="moon" x-show="isDarkMode" x-cloak></i>
                </button>
                <div x-data="{ open: false }" class="relative">
                    <button @click="open = !open" class="flex items-center gap-2">
                        <span class="hidden sm:inline"><?php if(auth()->guard()->check()): ?> <?php echo e(Auth::user()->name); ?> <?php else: ?> Guest <?php endif; ?></span>
                        <i data-feather="chevron-down" class="w-4 h-4"></i>
                    </button>
                    <div x-show="open" @click.away="open = false" x-cloak
                         class="absolute right-0 mt-2 w-48 bg-white dark:bg-gray-700 rounded-md shadow-lg py-1 z-50">
                        <form method="POST" action="<?php echo e(route('logout')); ?>">
                            <?php echo csrf_field(); ?>
                            <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-500 hover:bg-gray-100 dark:hover:bg-gray-600">Logout</button>
                        </form>
                    </div>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="flex-1 overflow-y-auto p-6 lg:p-8">
            <?php echo $__env->yieldContent('content'); ?>
        </main>

        <!-- Footer (RESTORED) -->
        <footer class="bg-white dark:bg-gray-800 p-4 text-center text-sm text-gray-500 dark:text-gray-400 shrink-0 border-t dark:border-gray-700">
            © <?php echo e(date('Y')); ?> Police Software Development Team. All rights reserved.
        </footer>

    </div>
</div>


<style>
    .nav-link { display: flex; align-items: center; gap: 0.75rem; padding: 0.5rem 1rem; border-radius: 0.375rem; color: oklch(35% 0.05 100); font-weight: 500; transition: background-color 0.2s, color 0.2s; white-space: nowrap; }
    .dark .nav-link { color: oklch(98% 0.02 100); }
    .nav-link:hover { background-color: rgba(0,0,0,0.05); }
    .dark .nav-link:hover { background-color: rgba(255,255,255,0.1); }
    .nav-link.active { background-color: rgba(0,0,0,0.1); color: oklch(25% 0.07 100); font-weight: 600; }
    .dark .nav-link.active { background-color: rgba(0,0,0,0.2); color: white; }
    .sidebar-collapsed .nav-link { justify-content: center; }
</style>


<script>
    function layout() {
        return {
            isSidebarOpen: false,
            isSidebarCollapsed: localStorage.getItem('sidebarCollapsed') === 'true',
            isDarkMode: localStorage.getItem('darkMode') === 'true',
            toggleTheme() { this.isDarkMode = !this.isDarkMode; localStorage.setItem('darkMode', this.isDarkMode); },
            init() { this.$watch('isSidebarCollapsed', value => { localStorage.setItem('sidebarCollapsed', value); setTimeout(() => feather.replace(), 50); }); feather.replace();
                <?php if(session('success')): ?>
                    Swal.fire({ icon: 'success', title: 'Success!', text: '<?php echo e(session('success')); ?>', timer: 3000, showConfirmButton: false });
                <?php endif; ?>
            }
        }
    }
    document.addEventListener('alpine:init', () => { Alpine.data('layout', layout); });
</script>

<?php echo $__env->yieldPushContent('scripts'); ?>

</body>
</html><?php /**PATH C:\inetpub\wwwroot\myproject\resources\views/layouts/app.blade.php ENDPATH**/ ?>