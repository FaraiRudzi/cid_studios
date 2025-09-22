

<?php $__env->startSection('title', 'Admin Dashboard'); ?>

<?php $__env->startSection('content'); ?>
<div class="container mx-auto">

    
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-200">Dashboard Overview</h1>
    </div>

    
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Stations Card -->
        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md flex items-center gap-4">
            <div class="bg-blue-100 dark:bg-blue-900/50 p-3 rounded-full">
                <i data-feather="map-pin" class="w-6 h-6 text-blue-600 dark:text-blue-400"></i>
            </div>
            <div>
                <p class="text-gray-500 dark:text-gray-400 text-sm">Total Stations</p>
                <p class="text-2xl font-bold text-gray-800 dark:text-gray-100"><?php echo e($totalStations); ?></p>
            </div>
        </div>
        <!-- Total Photographers Card -->
        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md flex items-center gap-4">
            <div class="bg-green-100 dark:bg-green-900/50 p-3 rounded-full">
                <i data-feather="camera" class="w-6 h-6 text-green-600 dark:text-green-400"></i>
            </div>
            <div>
                <p class="text-gray-500 dark:text-gray-400 text-sm">Total Photographers</p>
                <p class="text-2xl font-bold text-gray-800 dark:text-gray-100"><?php echo e($totalPhotographers); ?></p>
            </div>
        </div>
        <!-- Pending Cases Card -->
        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md flex items-center gap-4">
            <div class="bg-yellow-100 dark:bg-yellow-900/50 p-3 rounded-full">
                <i data-feather="alert-circle" class="w-6 h-6 text-yellow-600 dark:text-yellow-400"></i>
            </div>
            <div>
                <p class="text-gray-500 dark:text-gray-400 text-sm">Pending Cases</p>
                <p class="text-2xl font-bold text-gray-800 dark:text-gray-100"><?php echo e($pendingCases); ?></p>
            </div>
        </div>
        <!-- Finalised Cases Card -->
        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md flex items-center gap-4">
            <div class="bg-purple-100 dark:bg-purple-900/50 p-3 rounded-full">
                <i data-feather="check-circle" class="w-6 h-6 text-purple-600 dark:text-purple-400"></i>
            </div>
            <div>
                <p class="text-gray-500 dark:text-gray-400 text-sm">Finalised Cases</p>
                <p class="text-2xl font-bold text-gray-800 dark:text-gray-100"><?php echo e($finalisedCases); ?></p>
            </div>
        </div>
    </div>

    
    <div class="flex justify-center">
        <!-- Doughnut Chart: Case Status -->
        <div class="w-full md:w-2/3 lg:w-1/2 bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md">
            <h3 class="text-lg font-semibold mb-4 text-gray-800 dark:text-gray-200">Case Status Overview</h3>
            <canvas id="caseStatusChart"></canvas>
        </div>
    </div>

</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    // Define the color palette
    const policeBlue = 'rgba(0, 82, 160, 0.7)';
    const policeGold = 'rgba(212, 175, 55, 0.8)';
    const policeBlueBorder = 'rgba(0, 82, 160, 1)';
    const policeGoldBorder = 'rgba(212, 175, 55, 1)';
    const chartTextColor = document.documentElement.classList.contains('dark') ? '#E5E7EB' : '#374151';

    // --- Doughnut Chart for Case Status (with new colors and dark mode text) ---
    const caseStatusCtx = document.getElementById('caseStatusChart').getContext('2d');
    new Chart(caseStatusCtx, {
        type: 'doughnut',
        data: {
            labels: ['Pending', 'Finalised'],
            datasets: [{
                label: 'Case Status',
                data: [<?php echo json_encode($pendingCases, 15, 512) ?>, <?php echo json_encode($finalisedCases, 15, 512) ?>],
                backgroundColor: [
                    policeGold, // Gold for Pending
                    policeBlue  // Blue for Finalised
                ],
                borderColor: [
                    policeGoldBorder,
                    policeBlueBorder
                ],
                borderWidth: 1.5
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                    labels: {
                        color: chartTextColor // Make legend text dark-mode aware
                    }
                },
                title: {
                    display: true,
                    text: 'Case Status Distribution',
                    color: chartTextColor // Make title text dark-mode aware
                }
            }
        }
    });
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\inetpub\wwwroot\myproject\resources\views/admin/dashboard.blade.php ENDPATH**/ ?>