<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($pageTitle) ? e($pageTitle) . ' - ' : '' ?>Admin Panel - <?= APP_NAME ?></title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="<?= url('images/activity_logo.png') ?>">
    
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Tailwind Custom Colors Config -->
    <?= tailwind_config() ?>
    
    <!-- Google Fonts - Onest -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Onest:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        * { font-family: 'Onest', sans-serif; }
        
        /* Sidebar transition */
        #admin-sidebar {
            transition: width 0.3s ease, transform 0.3s ease;
        }
        
        #admin-sidebar.sidebar-hidden {
            transform: translateX(-100%);
        }
        
        /* Menu label smooth transition */
        .menu-label {
            transition: opacity 0.3s ease, width 0.3s ease;
        }
        
        /* Menu item smooth transition */
        .menu-item {
            transition: all 0.3s ease;
        }
        
        /* Logo section transition */
        #logo-section {
            transition: all 0.3s ease;
        }
    </style>
</head>
<body class="bg-white-secondary overflow-x-hidden">
    <!-- Toast Component -->
    <?php component('toast'); ?>
    
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar Component (Fixed) -->
        <?php if (!isset($hideSidebar) || !$hideSidebar): ?>
            <?php component('admin-sidebar', ['currentPage' => $currentPage ?? 'dashboard']); ?>
        <?php endif; ?>
        
        <!-- Main Content (Scrollable) -->
        <main id="admin-main-content" class="flex-1 overflow-y-auto transition-all duration-300 h-screen">
            <div class="p-8">
                <?php 
                $successMessage = flash('success');
                $errorMessage = flash('error');
                ?>
                
                <?php if ($successMessage): ?>
                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            showToast(<?= json_encode($successMessage) ?>, 'success', 4000);
                        });
                    </script>
                <?php endif; ?>
                
                <?php if ($errorMessage): ?>
                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            showToast(<?= json_encode($errorMessage) ?>, 'error', 5000);
                        });
                    </script>
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
