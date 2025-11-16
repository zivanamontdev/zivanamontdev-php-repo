<?php 
$pageTitle = 'Programs Management';
ob_start(); 
?>

<div class="mb-8 flex justify-between items-center">
    <div>
        <h1 class="text-3xl font-bold text-gray-900">Programs Management</h1>
        <p class="text-gray-600 mt-2">Manage school programs and activities</p>
    </div>
    <a href="<?= url('/admin/programs/create') ?>" class="bg-purple-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-purple-700">
        + Add Program
    </a>
</div>

<div class="bg-white rounded-lg shadow">
    <?php if (!empty($programs)): ?>
        <table class="w-full">
            <thead>
                <tr class="border-b bg-gray-50">
                    <th class="text-left p-4 font-semibold">ID</th>
                    <th class="text-left p-4 font-semibold">Name</th>
                    <th class="text-left p-4 font-semibold">Status</th>
                    <th class="text-left p-4 font-semibold">Display Order</th>
                    <th class="text-left p-4 font-semibold">Created</th>
                    <th class="text-right p-4 font-semibold">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($programs as $program): ?>
                    <tr class="border-b hover:bg-gray-50">
                        <td class="p-4"><?= e($program['id']) ?></td>
                        <td class="p-4 font-medium"><?= e($program['name']) ?></td>
                        <td class="p-4">
                            <?php if ($program['is_active']): ?>
                                <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm">Active</span>
                            <?php else: ?>
                                <span class="bg-gray-100 text-gray-800 px-3 py-1 rounded-full text-sm">Inactive</span>
                            <?php endif; ?>
                        </td>
                        <td class="p-4"><?= e($program['display_order']) ?></td>
                        <td class="p-4 text-sm text-gray-600"><?= format_date($program['created_at']) ?></td>
                        <td class="p-4 text-right">
                            <a href="<?= url('/admin/programs/' . $program['id'] . '/edit') ?>" class="text-blue-600 hover:text-blue-800 mr-3">Edit</a>
                            <form action="<?= url('/admin/programs/' . $program['id'] . '/delete') ?>" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this program?');">
                                <?= csrf_field() ?>
                                <button type="submit" class="text-red-600 hover:text-red-800">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <div class="p-12 text-center">
            <p class="text-gray-500 mb-4">No programs found</p>
            <a href="<?= url('/admin/programs/create') ?>" class="text-purple-600 hover:text-purple-700 font-semibold">Create your first program</a>
        </div>
    <?php endif; ?>
</div>

<?php 
$content = ob_get_clean();
require VIEW_PATH . '/layouts/admin.php';
?>
