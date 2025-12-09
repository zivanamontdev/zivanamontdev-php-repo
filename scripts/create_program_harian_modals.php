<?php
/**
 * Script to create modal-add-gallery-harian.php and modal-edit-gallery-harian.php
 * by copying from Program Tahun gallery modals with ID replacements
 */

define('ROOT_PATH', dirname(__DIR__));

// Read template files
$addGalleryTemplate = file_get_contents(ROOT_PATH . '/app/views/components/widget/activities/modals/gallery/modal-add-gallery-image.php');
$editGalleryTemplate = file_get_contents(ROOT_PATH . '/app/views/components/widget/activities/modals/gallery/modal-edit-gallery-image.php');

// Replace IDs for Program Harian Add Gallery
$addGalleryHarian = str_replace(
    [
        'modal-add-gallery-image',
        'close-modal-add-gallery-image',
        'preview-image-gallery',
        'placeholder-icon-gallery',
        'caution-text-gallery',
        'gallery-image-input',
        'delete-button-wrapper-gallery',
        'delete-image-btn-gallery',
        'form-add-gallery-image',
        'gallery-program-id',
        'photo-description',
        'set-as-cover',
        'submit-button-wrapper-gallery',
        'openAddGalleryImageModal',
        'localStorage.setItem(\'activeTabIndex\', \'1\')',
        '/admin/activities/programs-tahun/',
        '/gallery/store',
        'Modal for adding gallery image to program tahun ajaran'
    ],
    [
        'modal-add-gallery-harian',
        'close-modal-add-gallery-harian',
        'preview-image-gallery-harian',
        'placeholder-icon-gallery-harian',
        'caution-text-gallery-harian',
        'gallery-harian-image-input',
        'delete-button-wrapper-gallery-harian',
        'delete-image-btn-gallery-harian',
        'form-add-gallery-harian',
        'gallery-harian-program-id',
        'photo-description-harian',
        'set-as-cover-harian',
        'submit-button-wrapper-gallery-harian',
        'openAddGalleryHarianModal',
        'localStorage.setItem(\'activeTabIndex\', \'2\')',
        '/admin/activities/programs-harian/',
        '/gallery/store',
        'Modal for adding gallery image to program harian'
    ],
    $addGalleryTemplate
);

// Replace IDs for Program Harian Edit Gallery
$editGalleryHarian = str_replace(
    [
        'modal-edit-gallery-image',
        'close-modal-edit-gallery-image',
        'preview-image-gallery-edit',
        'placeholder-icon-gallery-edit',
        'caution-text-gallery-edit',
        'gallery-image-input-edit',
        'form-edit-gallery-image',
        'gallery-image-id-edit',
        'gallery-program-id-edit',
        'photo-description-edit',
        'set-as-cover-edit',
        'delete-gallery-photo-btn',
        'submit-button-wrapper-gallery-edit',
        'openEditGalleryImageModal',
        'validateEditGalleryForm',
        'localStorage.setItem(\'activeTabIndex\', \'1\')',
        '/admin/activities/programs-tahun/',
        '/update-program-image',
        '/gallery/',
        '/update',
        '/delete',
        'Modal for editing gallery image in program tahun ajaran'
    ],
    [
        'modal-edit-gallery-harian',
        'close-modal-edit-gallery-harian',
        'preview-image-gallery-harian-edit',
        'placeholder-icon-gallery-harian-edit',
        'caution-text-gallery-harian-edit',
        'gallery-harian-image-input-edit',
        'form-edit-gallery-harian',
        'gallery-harian-image-id-edit',
        'gallery-harian-program-id-edit',
        'photo-description-harian-edit',
        'set-as-cover-harian-edit',
        'delete-gallery-harian-photo-btn',
        'submit-button-wrapper-gallery-harian-edit',
        'openEditGalleryHarianModal',
        'validateEditGalleryHarianForm',
        'localStorage.setItem(\'activeTabIndex\', \'2\')',
        '/admin/activities/programs-harian/',
        '/update-program-image',
        '/gallery/',
        '/update',
        '/delete',
        'Modal for editing gallery image in program harian'
    ],
    $editGalleryTemplate
);

// Write files
$targetDir = ROOT_PATH . '/app/views/components/widget/activities/modals/program_harian';
if (!is_dir($targetDir)) {
    mkdir($targetDir, 0755, true);
}

file_put_contents($targetDir . '/modal-add-gallery-harian.php', $addGalleryHarian);
file_put_contents($targetDir . '/modal-edit-gallery-harian.php', $editGalleryHarian);

echo "✅ Modal gallery files for Program Harian created successfully!\n";
echo "   - modal-add-gallery-harian.php\n";
echo "   - modal-edit-gallery-harian.php\n";
