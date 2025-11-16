<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($pageTitle) ? e($pageTitle) . ' - ' : '' ?>Admin Panel - <?= APP_NAME ?></title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');
        * { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-gray-100">
    <div class="flex h-screen">
        <!-- Sidebar -->
        <aside class="w-64 bg-gray-900 text-white">
            <div class="p-6">
                <h2 class="text-2xl font-bold">Admin Panel</h2>
                <p class="text-gray-400 text-sm"><?= e(auth_user()['full_name']) ?></p>
            </div>
            
            <nav class="mt-6">
                <a href="<?= url('/admin/dashboard') ?>" class="flex items-center px-6 py-3 text-gray-300 hover:bg-gray-800 hover:text-white">
                    <span>📊 Dashboard</span>
                </a>
                <a href="<?= url('/admin/programs') ?>" class="flex items-center px-6 py-3 text-gray-300 hover:bg-gray-800 hover:text-white">
                    <span>📚 Programs</span>
                </a>
                <a href="<?= url('/admin/articles') ?>" class="flex items-center px-6 py-3 text-gray-300 hover:bg-gray-800 hover:text-white">
                    <span>📰 Articles</span>
                </a>
                <a href="<?= url('/admin/employees') ?>" class="flex items-center px-6 py-3 text-gray-300 hover:bg-gray-800 hover:text-white">
                    <span>👥 Employees</span>
                </a>
                
                <div class="px-6 py-3 text-gray-500 text-sm font-semibold uppercase">Management</div>
                <a href="<?= url('/admin/management/schedules') ?>" class="flex items-center px-6 py-3 text-gray-300 hover:bg-gray-800 hover:text-white">
                    <span>🕐 Schedules</span>
                </a>
                <a href="<?= url('/admin/management/awards') ?>" class="flex items-center px-6 py-3 text-gray-300 hover:bg-gray-800 hover:text-white">
                    <span>🏆 Awards</span>
                </a>
                <a href="<?= url('/admin/management/social-media') ?>" class="flex items-center px-6 py-3 text-gray-300 hover:bg-gray-800 hover:text-white">
                    <span>📱 Social Media</span>
                </a>
                <a href="<?= url('/admin/management/settings') ?>" class="flex items-center px-6 py-3 text-gray-300 hover:bg-gray-800 hover:text-white">
                    <span>⚙️ Settings</span>
                </a>
            </nav>
            
            <div class="absolute bottom-0 w-64 p-6">
                <a href="<?= url('/') ?>" target="_blank" class="block px-4 py-2 text-center text-gray-300 hover:bg-gray-800 rounded mb-2">
                    View Website
                </a>
                <a href="<?= url('/admin/logout') ?>" class="block px-4 py-2 text-center bg-red-600 hover:bg-red-700 rounded">
                    Logout
                </a>
            </div>
        </aside>
        
        <!-- Main Content -->
        <main class="flex-1 overflow-y-auto">
            <div class="p-8">
                <?php if (flash('success')): ?>
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-6" role="alert">
                        <span class="block sm:inline"><?= e(flash('success')) ?></span>
                    </div>
                <?php endif; ?>
                
                <?php if (flash('error')): ?>
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-6" role="alert">
                        <span class="block sm:inline"><?= e(flash('error')) ?></span>
                    </div>
                <?php endif; ?>
                
                <?= $content ?? '' ?>
            </div>
        </main>
    </div>
    
    <script>
        setTimeout(function() {
            const alerts = document.querySelectorAll('[role="alert"]');
            alerts.forEach(function(alert) {
                alert.style.transition = 'opacity 0.5s';
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 500);
            });
        }, 5000);
    </script>
</body>
</html>
