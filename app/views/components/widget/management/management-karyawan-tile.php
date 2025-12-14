<?php
/**
 * Management Karyawan Tile Component
 * Displays employee information with drag handle and edit button
 */

$showDragHandle = $showDragHandle ?? true;
$photo = $photo ?? '';
$name = $name ?? 'Nama Karyawan';
$role = $role ?? 'Role';
$employeeId = $employeeId ?? '';
$onEdit = $onEdit ?? '';
?>

<div class="w-full flex items-center gap-4" data-employee-id="<?= e($employeeId) ?>">
    <!-- Drag Handle Icon (24x24) -->
    <?php if ($showDragHandle): ?>
        <div class="drag-handle cursor-move flex-shrink-0">
            <svg class="w-6 h-6 text-black-highlight" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <circle cx="9" cy="5" r="1.5"></circle>
                <circle cx="9" cy="12" r="1.5"></circle>
                <circle cx="9" cy="19" r="1.5"></circle>
                <circle cx="15" cy="5" r="1.5"></circle>
                <circle cx="15" cy="12" r="1.5"></circle>
                <circle cx="15" cy="19" r="1.5"></circle>
            </svg>
        </div>
    <?php endif; ?>
    
    <!-- Photo Placeholder (62x62) -->
    <div class="w-[62px] h-[62px] rounded-lg bg-gray-placeholder flex-shrink-0 flex items-center justify-center overflow-hidden">
        <?php if (!empty($photo)): ?>
            <img src="<?= e($photo) ?>" alt="<?= e($name) ?>" class="w-full h-full object-cover">
        <?php else: ?>
            <svg class="w-8 h-8 text-white-shadow" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
            </svg>
        <?php endif; ?>
    </div>
    
    <!-- Content (Name and Role) -->
    <div class="flex-1 flex flex-col justify-center">
        <h4 class="font-bold text-[12px] text-text-dark"><?= e($name) ?></h4>
        <p class="font-normal text-[12px] text-black-highlight"><?= e($role) ?></p>
    </div>
    
    <!-- Edit Button -->
    <div class="flex-shrink-0">
        <?php component('button', [
            'variant' => '9',
            'icon' => 'edit',
            'type' => 'button',
            'class' => 'edit-employee-btn',
            'attrs' => [
                'data-employee-id' => $employeeId,
                'onclick' => $onEdit
            ]
        ]); ?>
    </div>
</div>
