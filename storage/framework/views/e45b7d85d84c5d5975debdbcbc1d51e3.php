

<?php $__env->startSection('title', 'My Assigned Cases'); ?>

<?php $__env->startSection('content'); ?>
<div class="container mx-auto">

    
    <div class="max-w-6xl mx-auto">
        <div class="mb-4">
            <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-200">
                My Assigned Cases
            </h1>
        </div>

        <div class="mb-6 bg-white dark:bg-gray-800 p-4 rounded-lg shadow-md flex flex-col sm:flex-row items-center justify-end gap-4">
            <div class="flex flex-col sm:flex-row items-center gap-4 w-full sm:w-auto">
                <form method="GET" action="<?php echo e(route('photographer.dashboard')); ?>" class="flex items-center gap-2">
                    <input type="hidden" name="search" value="<?php echo e(request('search')); ?>">
                    <label for="per_page" class="text-sm font-medium text-gray-600 dark:text-gray-400">Show:</label>
                    <select name="per_page" id="per_page" class="border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300 rounded-md shadow-sm text-sm" onchange="this.form.submit()">
                        <option value="10" <?php if(request('per_page', 10) == 10): echo 'selected'; endif; ?>>10</option>
                        <option value="25" <?php if(request('per_page') == 25): echo 'selected'; endif; ?>>25</option>
                        <option value="50" <?php if(request('per_page') == 50): echo 'selected'; endif; ?>>50</option>
                    </select>
                </form>
                <form method="GET" action="<?php echo e(route('photographer.dashboard')); ?>" class="flex items-center gap-2 w-full sm:w-auto">
                    <input type="hidden" name="per_page" value="<?php echo e(request('per_page', 10)); ?>">
                    <input type="text" name="search" value="<?php echo e(request('search')); ?>" placeholder="Search your cases..." class="w-full sm:w-56 border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300 rounded-md shadow-sm text-sm">
                    <button type="submit" class="bg-blue-600 text-white p-2 rounded-md hover:bg-blue-700" title="Search"><i data-feather="search" class="w-5 h-5"></i></button>
                    <a href="<?php echo e(route('photographer.dashboard')); ?>" class="bg-gray-200 dark:bg-gray-600 text-gray-700 dark:text-gray-300 p-2 rounded-md hover:bg-gray-300 dark:hover:bg-gray-500" title="Reset Filter"><i data-feather="refresh-cw" class="w-5 h-5"></i></a>
                </form>
            </div>
        </div>
    </div>

    
    <div class="max-w-6xl mx-auto bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden">
        
        <table class="min-w-full">
            <thead class="bg-gray-50 dark:bg-gray-700/50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                        Case Details
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                        Status
                    </th>
                    <th scope="col" class="px-6 py-3 text-right text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                        Action
                    </th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                <?php $__empty_1 = true; $__currentLoopData = $cases; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $case): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors duration-200">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900 dark:text-gray-100"><?php echo e($case->reference_number); ?></div>
                        <div class="text-sm text-gray-500 dark:text-gray-400"><?php echo e($case->station->name ?? 'N/A'); ?></div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                        <?php if($case->cause_of_death): ?>
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-purple-100 text-purple-800 dark:bg-purple-900/50 dark:text-purple-300">Finalised</span>
                        <?php else: ?>
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-900/50 dark:text-yellow-300">Pending</span>
                        <?php endif; ?>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                        <a href="<?php echo e(route('photographer.case.show', $case)); ?>" class="text-indigo-600 dark:text-indigo-400 hover:underline font-semibold inline-flex items-center gap-1">
                            <i data-feather="eye" class="w-4 h-4"></i>
                            View & Upload
                        </a>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="3" class="text-center py-10">
                        <div class="flex flex-col items-center">
                            <i data-feather="folder" class="w-12 h-12 text-gray-300 dark:text-gray-600"></i>
                            <h3 class="mt-2 text-lg font-medium text-gray-900 dark:text-gray-200">No Cases Assigned</h3>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">You do not have any cases assigned to you at the moment.</p>
                        </div>
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
        
        <?php if($cases->hasPages()): ?>
        <div class="p-4 bg-gray-50 dark:bg-gray-700/50 border-t dark:border-gray-700">
            <?php echo e($cases->links()); ?>

        </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.photographer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\inetpub\wwwroot\myproject\resources\views/photographer/dashboard.blade.php ENDPATH**/ ?>