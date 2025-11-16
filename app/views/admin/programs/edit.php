<?php 
$pageTitle = 'Edit Program';
ob_start(); 
?>

<div class="mb-8">
    <a href="<?= url('/admin/programs') ?>" class="text-purple-600 hover:text-purple-700 mb-4 inline-block">← Back to Programs</a>
    <h1 class="text-3xl font-bold text-gray-900">Edit Program</h1>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Program Form -->
    <div class="lg:col-span-2">
        <div class="bg-white rounded-lg shadow p-8">
            <form action="<?= url('/admin/programs/' . $program['id'] . '/update') ?>" method="POST">
                <?= csrf_field() ?>
                
                <div class="mb-6">
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Program Name <span class="text-red-500">*</span></label>
                    <input type="text" id="name" name="name" value="<?= e($program['name']) ?>" required
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-600 focus:border-transparent">
                </div>
                
                <div class="mb-6">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description <span class="text-red-500">*</span></label>
                    <textarea id="description" name="description" rows="8" required
                              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-600 focus:border-transparent"><?= e($program['description']) ?></textarea>
                </div>
                
                <div class="mb-6">
                    <label for="display_order" class="block text-sm font-medium text-gray-700 mb-2">Display Order</label>
                    <input type="number" id="display_order" name="display_order" value="<?= e($program['display_order']) ?>"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-600 focus:border-transparent">
                </div>
                
                <div class="mb-6">
                    <label class="flex items-center">
                        <input type="checkbox" name="is_active" value="1" <?= $program['is_active'] ? 'checked' : '' ?> class="mr-2">
                        <span class="text-sm font-medium text-gray-700">Active</span>
                    </label>
                </div>
                
                <button type="submit" class="bg-purple-600 text-white px-8 py-3 rounded-lg font-semibold hover:bg-purple-700">
                    Update Program
                </button>
            </form>
        </div>
    </div>
    
    <!-- Images Section -->
    <div class="lg:col-span-1">
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <h3 class="font-bold text-lg mb-4">Upload Images</h3>
            <form action="<?= url('/admin/programs/' . $program['id'] . '/upload-image') ?>" method="POST" enctype="multipart/form-data">
                <?= csrf_field() ?>
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Image</label>
                    <input type="file" name="image" accept="image/*" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                </div>
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Caption</label>
                    <input type="text" name="caption"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                </div>
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Display Order</label>
                    <input type="number" name="display_order" value="0"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                </div>
                
                <button type="submit" class="w-full bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700">
                    Upload Image
                </button>
            </form>
        </div>
        
        <!-- Current Images -->
        <?php if (!empty($program['images'])): ?>
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="font-bold text-lg mb-4">Program Images</h3>
                <div class="space-y-4">
                    <?php foreach ($program['images'] as $image): ?>
                        <div class="border rounded-lg p-3">
                            <img src="<?= upload($image['filename']) ?>" alt="<?= e($image['caption']) ?>" class="w-full h-32 object-cover rounded mb-2">
                            <?php if ($image['caption']): ?>
                                <p class="text-sm text-gray-600 mb-2"><?= e($image['caption']) ?></p>
                            <?php endif; ?>
                            <form action="<?= url('/admin/programs/' . $program['id'] . '/delete-image/' . $image['id']) ?>" method="POST" onsubmit="return confirm('Delete this image?');">
                                <?= csrf_field() ?>
                                <button type="submit" class="text-red-600 hover:text-red-800 text-sm">Delete</button>
                            </form>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php 
$content = ob_get_clean();
require VIEW_PATH . '/layouts/admin.php';
?>
