<?php 
$pageTitle = 'Dashboard';
ob_start(); 
?>

<div class="mb-8">
    <h1 class="text-3xl font-bold text-gray-900">Dashboard</h1>
    <p class="text-gray-600 mt-2">Welcome back, <?= e(auth_user()['full_name']) ?>!</p>
</div>

<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-600 text-sm">Total Visits (30 days)</p>
                <p class="text-3xl font-bold text-gray-900 mt-2"><?= number_format($totalVisits) ?></p>
            </div>
            <div class="bg-blue-100 p-3 rounded-full">
                <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                </svg>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-600 text-sm">Registrations</p>
                <p class="text-3xl font-bold text-gray-900 mt-2"><?= number_format($totalRegistrations) ?></p>
            </div>
            <div class="bg-green-100 p-3 rounded-full">
                <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-600 text-sm">Published Articles</p>
                <p class="text-3xl font-bold text-gray-900 mt-2"><?= count($articles ?? []) ?></p>
            </div>
            <div class="bg-purple-100 p-3 rounded-full">
                <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path>
                </svg>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-600 text-sm">Active Programs</p>
                <p class="text-3xl font-bold text-gray-900 mt-2"><?= count($programs ?? []) ?></p>
            </div>
            <div class="bg-yellow-100 p-3 rounded-full">
                <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                </svg>
            </div>
        </div>
    </div>
</div>

<!-- Charts and Tables -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
    <!-- Visits Chart -->
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-bold mb-4">Visits Last 7 Days</h3>
        <div class="space-y-3">
            <?php if (!empty($visitsByDate)): ?>
                <?php 
                $maxVisits = max(array_column($visitsByDate, 'count'));
                foreach ($visitsByDate as $visit): 
                    $percentage = $maxVisits > 0 ? ($visit['count'] / $maxVisits * 100) : 0;
                ?>
                    <div>
                        <div class="flex justify-between text-sm mb-1">
                            <span><?= date('M d', strtotime($visit['date'])) ?></span>
                            <span class="font-semibold"><?= $visit['count'] ?> visits</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-purple-600 h-2 rounded-full" style="width: <?= $percentage ?>%"></div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="text-gray-500 text-center py-4">No visit data available</p>
            <?php endif; ?>
        </div>
    </div>
    
    <!-- Device Stats -->
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-bold mb-4">Devices</h3>
        <div class="space-y-4">
            <?php if (!empty($deviceStats)): ?>
                <?php 
                $totalDevices = array_sum(array_column($deviceStats, 'count'));
                foreach ($deviceStats as $device): 
                    $percentage = $totalDevices > 0 ? ($device['count'] / $totalDevices * 100) : 0;
                ?>
                    <div>
                        <div class="flex justify-between text-sm mb-1">
                            <span><?= e($device['device_type']) ?></span>
                            <span class="font-semibold"><?= number_format($percentage, 1) ?>%</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-indigo-600 h-2 rounded-full" style="width: <?= $percentage ?>%"></div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="text-gray-500 text-center py-4">No device data available</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Top Pages -->
<div class="bg-white rounded-lg shadow mb-8">
    <div class="p-6 border-b">
        <h3 class="text-lg font-bold">Top Pages</h3>
    </div>
    <div class="p-6">
        <?php if (!empty($topPages)): ?>
            <table class="w-full">
                <thead>
                    <tr class="text-left border-b">
                        <th class="pb-3 font-semibold">Page URL</th>
                        <th class="pb-3 font-semibold text-right">Visits</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($topPages as $page): ?>
                        <tr class="border-b">
                            <td class="py-3"><?= e($page['page_url']) ?></td>
                            <td class="py-3 text-right font-semibold"><?= number_format($page['count']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p class="text-gray-500 text-center py-4">No page data available</p>
        <?php endif; ?>
    </div>
</div>

<!-- Recent Registrations -->
<div class="bg-white rounded-lg shadow">
    <div class="p-6 border-b flex justify-between items-center">
        <h3 class="text-lg font-bold">Recent Registrations</h3>
        <a href="<?= url('/admin/registrations') ?>" class="text-purple-600 hover:text-purple-700 text-sm font-semibold">View All</a>
    </div>
    <div class="p-6">
        <?php if (!empty($recentRegistrations)): ?>
            <table class="w-full">
                <thead>
                    <tr class="text-left border-b">
                        <th class="pb-3 font-semibold">Child Name</th>
                        <th class="pb-3 font-semibold">Parent Name</th>
                        <th class="pb-3 font-semibold">Contact</th>
                        <th class="pb-3 font-semibold">Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($recentRegistrations as $reg): ?>
                        <tr class="border-b">
                            <td class="py-3"><?= e($reg['child_name']) ?></td>
                            <td class="py-3"><?= e($reg['parent_name']) ?></td>
                            <td class="py-3"><?= e($reg['phone']) ?></td>
                            <td class="py-3 text-sm text-gray-600"><?= format_datetime($reg['created_at']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p class="text-gray-500 text-center py-4">No registrations yet</p>
        <?php endif; ?>
    </div>
</div>

<?php 
$content = ob_get_clean();
require VIEW_PATH . '/layouts/admin.php';
?>
