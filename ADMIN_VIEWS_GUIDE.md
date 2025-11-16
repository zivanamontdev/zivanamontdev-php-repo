# Admin Views Template Guide

This guide shows the structure for creating the remaining admin CRUD views. All views follow the same pattern.

## View Structure Pattern

Each resource (Articles, Employees, etc.) needs 3 views:

1. **index.php** - List all items
2. **create.php** - Form to create new item
3. **edit.php** - Form to edit existing item

## Template Examples

### Articles Views

**File:** `app/views/admin/articles/index.php`
- List all articles with title, author, status, date
- Actions: Edit, Delete
- Add Article button

**File:** `app/views/admin/articles/create.php`
- Form fields: title, content (textarea), excerpt, author_name, featured_image (file upload), status (draft/published), is_featured (checkbox)
- Submit to `/admin/articles/store`

**File:** `app/views/admin/articles/edit.php`
- Same form as create but with existing data
- Submit to `/admin/articles/{id}/update`
- Show current featured image if exists

### Employees Views

**File:** `app/views/admin/employees/index.php`
- List all employees with name, position, level, status
- Group by level (optional)
- Actions: Edit, Delete

**File:** `app/views/admin/employees/create.php`
- Form fields: full_name, position, level (select: leadership, educational_support, teaching_staff, operational_staff), photo (file upload), bio (textarea), display_order, is_active (checkbox)
- Submit to `/admin/employees/store`

**File:** `app/views/admin/employees/edit.php`
- Same form as create with existing data
- Submit to `/admin/employees/{id}/update`
- Show current photo if exists

### Management Views (Schedules, Awards, Social Media, Settings)

**File:** `app/views/admin/management/schedules.php`
- List all schedules with time and activity
- Inline create form at top
- Inline edit forms (or modal)
- Delete buttons

**File:** `app/views/admin/management/awards.php`
- List all awards with image thumbnails
- Create form with image upload
- Edit inline or in modal
- Delete with confirmation

**File:** `app/views/admin/management/social-media.php`
- List all social media accounts
- Create form: platform (select), account_name, url
- Edit and delete buttons

**File:** `app/views/admin/management/settings.php`
- List all settings grouped by category
- Form with all settings as inputs
- Submit all at once to `/admin/management/settings/update`

## Quick Copy-Paste Templates

### Basic Index Template
```php
<?php 
$pageTitle = 'Resource Management';
ob_start(); 
?>

<div class="mb-8 flex justify-between items-center">
    <h1 class="text-3xl font-bold">Resource Management</h1>
    <a href="<?= url('/admin/resource/create') ?>" class="bg-purple-600 text-white px-6 py-3 rounded-lg">+ Add New</a>
</div>

<div class="bg-white rounded-lg shadow">
    <table class="w-full">
        <thead>
            <tr class="border-b bg-gray-50">
                <th class="text-left p-4">Column</th>
                <th class="text-right p-4">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($items as $item): ?>
                <tr class="border-b">
                    <td class="p-4"><?= e($item['name']) ?></td>
                    <td class="p-4 text-right">
                        <a href="<?= url('/admin/resource/' . $item['id'] . '/edit') ?>" class="text-blue-600">Edit</a>
                        <form action="<?= url('/admin/resource/' . $item['id'] . '/delete') ?>" method="POST" class="inline">
                            <?= csrf_field() ?>
                            <button type="submit" class="text-red-600">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php 
$content = ob_get_clean();
require VIEW_PATH . '/layouts/admin.php';
?>
```

### Basic Create Template
```php
<?php 
$pageTitle = 'Create Resource';
ob_start(); 
?>

<div class="mb-8">
    <a href="<?= url('/admin/resource') ?>" class="text-purple-600">← Back</a>
    <h1 class="text-3xl font-bold">Create New Resource</h1>
</div>

<div class="bg-white rounded-lg shadow p-8 max-w-3xl">
    <form action="<?= url('/admin/resource/store') ?>" method="POST" enctype="multipart/form-data">
        <?= csrf_field() ?>
        
        <div class="mb-6">
            <label class="block text-sm font-medium mb-2">Field Name</label>
            <input type="text" name="field" required class="w-full px-4 py-3 border rounded-lg">
        </div>
        
        <button type="submit" class="bg-purple-600 text-white px-8 py-3 rounded-lg">Create</button>
    </form>
</div>

<?php 
$content = ob_get_clean();
require VIEW_PATH . '/layouts/admin.php';
?>
```

### Basic Edit Template
```php
<?php 
$pageTitle = 'Edit Resource';
ob_start(); 
?>

<div class="mb-8">
    <a href="<?= url('/admin/resource') ?>" class="text-purple-600">← Back</a>
    <h1 class="text-3xl font-bold">Edit Resource</h1>
</div>

<div class="bg-white rounded-lg shadow p-8 max-w-3xl">
    <form action="<?= url('/admin/resource/' . $item['id'] . '/update') ?>" method="POST" enctype="multipart/form-data">
        <?= csrf_field() ?>
        
        <div class="mb-6">
            <label class="block text-sm font-medium mb-2">Field Name</label>
            <input type="text" name="field" value="<?= e($item['field']) ?>" required class="w-full px-4 py-3 border rounded-lg">
        </div>
        
        <button type="submit" class="bg-purple-600 text-white px-8 py-3 rounded-lg">Update</button>
    </form>
</div>

<?php 
$content = ob_get_clean();
require VIEW_PATH . '/layouts/admin.php';
?>
```

## Creating Views

To create any missing view:

1. Copy the appropriate template above
2. Replace "Resource" with your actual resource name
3. Update form fields according to your database schema
4. Update URLs to match your routes
5. Add any specific styling or functionality

## Form Field Types Reference

```php
<!-- Text Input -->
<input type="text" name="field_name" value="<?= e($value) ?>" class="w-full px-4 py-3 border rounded-lg">

<!-- Email Input -->
<input type="email" name="email" value="<?= e($value) ?>" class="w-full px-4 py-3 border rounded-lg">

<!-- Number Input -->
<input type="number" name="number" value="<?= e($value) ?>" class="w-full px-4 py-3 border rounded-lg">

<!-- Textarea -->
<textarea name="field_name" rows="6" class="w-full px-4 py-3 border rounded-lg"><?= e($value) ?></textarea>

<!-- Select Dropdown -->
<select name="field_name" class="w-full px-4 py-3 border rounded-lg">
    <option value="option1" <?= $value == 'option1' ? 'selected' : '' ?>>Option 1</option>
    <option value="option2" <?= $value == 'option2' ? 'selected' : '' ?>>Option 2</option>
</select>

<!-- Checkbox -->
<input type="checkbox" name="field_name" value="1" <?= $value ? 'checked' : '' ?> class="mr-2">

<!-- File Upload -->
<input type="file" name="file_field" accept="image/*" class="w-full px-3 py-2 border rounded-lg">

<!-- Date Input -->
<input type="date" name="date_field" value="<?= e($value) ?>" class="w-full px-4 py-3 border rounded-lg">

<!-- Time Input -->
<input type="time" name="time_field" value="<?= e($value) ?>" class="w-full px-4 py-3 border rounded-lg">
```

## Required vs Optional Fields

Add `required` attribute to make field mandatory:
```php
<input type="text" name="required_field" required>
```

Add asterisk (*) to label for required fields:
```php
<label>Field Name <span class="text-red-500">*</span></label>
```

## File Uploads

Always add `enctype="multipart/form-data"` to form:
```php
<form method="POST" enctype="multipart/form-data">
```

Show existing image:
```php
<?php if ($item['image']): ?>
    <img src="<?= upload($item['image']) ?>" alt="Current image" class="w-32 h-32 object-cover rounded mb-2">
<?php endif; ?>
```

## Implementation Priority

Create views in this order:

1. ✅ **Programs** (Already created)
2. **Articles** - Most important for content
3. **Employees** - For team showcase
4. **Schedules** - Simple list management
5. **Awards** - Image gallery
6. **Social Media** - Quick links
7. **Settings** - Configuration

## Notes

- All controllers are already created and working
- Routes are already defined
- Models handle all database operations
- You only need to create the view files
- Follow the existing Programs views as reference
- Use Tailwind CSS classes for styling
- CSRF protection is already implemented
- File upload handling is in helper functions

## Testing

After creating views:
1. Login to admin panel
2. Navigate to each section
3. Test Create, Edit, Delete operations
4. Verify data appears correctly on public pages
5. Check file uploads work properly

Good luck! 🚀
