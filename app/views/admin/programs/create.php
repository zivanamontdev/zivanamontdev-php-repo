<?php 
$pageTitle = 'Create Program';
ob_start(); 
?>

<div class="mb-8">
    <a href="<?= url('/admin/programs') ?>" class="text-purple-600 hover:text-purple-700 mb-4 inline-block">← Back to Programs</a>
    <h1 class="text-3xl font-bold text-gray-900">Create New Program</h1>
</div>

<div class="bg-white rounded-lg shadow p-8 max-w-3xl">
    <form action="<?= url('/admin/programs/store') ?>" method="POST">
        <?= csrf_field() ?>
        
        <div class="mb-6">
            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Program Name <span class="text-red-500">*</span></label>
            <input type="text" id="name" name="name" value="<?= e(old('name')) ?>" required
                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-600 focus:border-transparent">
        </div>
        
        <div class="mb-6">
            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description <span class="text-red-500">*</span></label>
            <textarea id="description" name="description" rows="8" required
                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-600 focus:border-transparent"><?= e(old('description')) ?></textarea>
            <p class="text-sm text-gray-500 mt-1">HTML tags are allowed</p>
        </div>
        
        <div class="mb-6">
            <label for="display_order" class="block text-sm font-medium text-gray-700 mb-2">Display Order</label>
            <input type="number" id="display_order" name="display_order" value="<?= e(old('display_order', 0)) ?>"
                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-600 focus:border-transparent">
            <p class="text-sm text-gray-500 mt-1">Lower numbers appear first</p>
        </div>
        
        <div class="mb-6">
            <label class="flex items-center">
                <input type="checkbox" name="is_active" value="1" <?= old('is_active', 1) ? 'checked' : '' ?> class="mr-2">
                <span class="text-sm font-medium text-gray-700">Active</span>
            </label>
        </div>
        
        <div class="flex space-x-4">
            <button type="submit" class="bg-purple-600 text-white px-8 py-3 rounded-lg font-semibold hover:bg-purple-700">
                Create Program
            </button>
            <a href="<?= url('/admin/programs') ?>" class="bg-gray-200 text-gray-700 px-8 py-3 rounded-lg font-semibold hover:bg-gray-300">
                Cancel
            </a>
        </div>
    </form>
</div>

<?php 
$content = ob_get_clean();
require VIEW_PATH . '/layouts/admin.php';
?>
