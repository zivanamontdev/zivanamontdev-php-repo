<?php
/**
 * Admin Navbar Component
 * 
 * @param string $title - Page title to display
 */

$title = $title ?? 'Dashboard';
$user = auth_user();
?>

<!-- Admin Navbar -->
<div class="mb-8 flex items-center justify-between">
    <!-- Title -->
    <h1 class="text-[20px] font-normal text-gray-900"><?= e($title) ?></h1>
    
    <!-- User Profile Card -->
    <div class="bg-white-neutral p-2 rounded-xl flex items-center gap-3">
        <!-- User Icon -->
        <div class="w-6 h-6 text-black-neutral flex-shrink-0">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                <path d="M12 12C14.7614 12 17 9.76142 17 7C17 4.23858 14.7614 2 12 2C9.23858 2 7 4.23858 7 7C7 9.76142 9.23858 12 12 12Z"/>
                <path d="M12 14.5C6.99 14.5 2.91 17.86 2.91 22C2.91 22.28 3.13 22.5 3.41 22.5H20.59C20.87 22.5 21.09 22.28 21.09 22C21.09 17.86 17.01 14.5 12 14.5Z"/>
            </svg>
        </div>
        <!-- User Info -->
        <div class="flex flex-col">
            <span class="text-[12px] font-normal text-black-neutral leading-tight"><?= e($user['full_name'] ?? 'Administrator') ?></span>
            <span class="text-[10px] font-medium text-white-shadow leading-tight mt-0.5"><?= e($user['email'] ?? 'admin@zivana.sch.id') ?></span>
        </div>
    </div>
</div>
