<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?= e($settings['school_description'] ?? 'Quality Montessori education for your child') ?>">
    <title><?= isset($pageTitle) ? e($pageTitle) . ' - ' : '' ?><?= e($settings['school_name'] ?? APP_NAME) ?></title>
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Custom Styles -->
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');
        
        * {
            font-family: 'Inter', sans-serif;
        }
        
        .hero-gradient {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        .card-hover {
            transition: all 0.3s ease;
        }
        
        .card-hover:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
        }
        
        .animate-fade-in {
            animation: fadeIn 0.6s ease-in;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .prose img {
            border-radius: 0.5rem;
        }
    </style>
    
    <?php if (isset($customStyles)): ?>
        <?= $customStyles ?>
    <?php endif; ?>
</head>
<body class="bg-gray-50">
    <!-- Navigation -->
    <nav class="bg-white shadow-lg fixed w-full top-0 z-50">
        <div class="container mx-auto px-4">
            <div class="flex justify-between items-center py-4">
                <a href="<?= url('/') ?>" class="text-2xl font-bold text-purple-600">
                    <?= e($settings['school_name'] ?? APP_NAME) ?>
                </a>
                
                <!-- Mobile menu button -->
                <button id="mobile-menu-button" class="md:hidden text-gray-600 hover:text-purple-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
                
                <!-- Desktop menu -->
                <div class="hidden md:flex space-x-8">
                    <a href="<?= url('/') ?>" class="text-gray-700 hover:text-purple-600 font-medium">Home</a>
                    <a href="<?= url('/activities') ?>" class="text-gray-700 hover:text-purple-600 font-medium">Activities</a>
                    <a href="<?= url('/profile') ?>" class="text-gray-700 hover:text-purple-600 font-medium">Profile</a>
                    <a href="<?= url('/articles') ?>" class="text-gray-700 hover:text-purple-600 font-medium">Articles</a>
                    <a href="<?= url('/registration') ?>" class="bg-purple-600 text-white px-6 py-2 rounded-full hover:bg-purple-700 font-medium">Register Now</a>
                </div>
            </div>
            
            <!-- Mobile menu -->
            <div id="mobile-menu" class="hidden md:hidden pb-4">
                <div class="flex flex-col space-y-3">
                    <a href="<?= url('/') ?>" class="text-gray-700 hover:text-purple-600 font-medium">Home</a>
                    <a href="<?= url('/activities') ?>" class="text-gray-700 hover:text-purple-600 font-medium">Activities</a>
                    <a href="<?= url('/profile') ?>" class="text-gray-700 hover:text-purple-600 font-medium">Profile</a>
                    <a href="<?= url('/articles') ?>" class="text-gray-700 hover:text-purple-600 font-medium">Articles</a>
                    <a href="<?= url('/registration') ?>" class="bg-purple-600 text-white px-6 py-2 rounded-full hover:bg-purple-700 font-medium text-center">Register Now</a>
                </div>
            </div>
        </div>
    </nav>
    
    <!-- Main Content -->
    <main class="pt-20">
        <?php if (flash('success')): ?>
            <div class="container mx-auto px-4 pt-4">
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline"><?= e(flash('success')) ?></span>
                </div>
            </div>
        <?php endif; ?>
        
        <?php if (flash('error')): ?>
            <div class="container mx-auto px-4 pt-4">
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline"><?= e(flash('error')) ?></span>
                </div>
            </div>
        <?php endif; ?>
        
        <?= $content ?? '' ?>
    </main>
    
    <!-- Footer -->
    <footer class="bg-gray-900 text-white mt-20">
        <div class="container mx-auto px-4 py-12">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div>
                    <h3 class="text-2xl font-bold mb-4"><?= e($settings['school_name'] ?? APP_NAME) ?></h3>
                    <p class="text-gray-400"><?= e($settings['school_description'] ?? 'Quality Montessori education for your child') ?></p>
                </div>
                
                <div>
                    <h4 class="text-lg font-semibold mb-4">Contact</h4>
                    <ul class="space-y-2 text-gray-400">
                        <?php if (!empty($settings['school_address'])): ?>
                            <li><?= e($settings['school_address']) ?></li>
                        <?php endif; ?>
                        <?php if (!empty($settings['school_phone'])): ?>
                            <li>Phone: <?= e($settings['school_phone']) ?></li>
                        <?php endif; ?>
                        <?php if (!empty($settings['school_email'])): ?>
                            <li>Email: <?= e($settings['school_email']) ?></li>
                        <?php endif; ?>
                    </ul>
                </div>
                
                <div>
                    <h4 class="text-lg font-semibold mb-4">Follow Us</h4>
                    <div class="flex space-x-4">
                        <?php if (isset($socialMedia) && !empty($socialMedia)): ?>
                            <?php foreach ($socialMedia as $social): ?>
                                <a href="<?= e($social['url']) ?>" target="_blank" class="text-gray-400 hover:text-white">
                                    <?= e($social['platform']) ?>
                                </a>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            
            <div class="border-t border-gray-800 mt-8 pt-8 text-center text-gray-400">
                <p>&copy; <?= date('Y') ?> <?= e($settings['school_name'] ?? APP_NAME) ?>. All rights reserved.</p>
            </div>
        </div>
    </footer>
    
    <script>
        // Mobile menu toggle
        document.getElementById('mobile-menu-button').addEventListener('click', function() {
            document.getElementById('mobile-menu').classList.toggle('hidden');
        });
        
        // Auto-hide flash messages after 5 seconds
        setTimeout(function() {
            const alerts = document.querySelectorAll('[role="alert"]');
            alerts.forEach(function(alert) {
                alert.style.transition = 'opacity 0.5s';
                alert.style.opacity = '0';
                setTimeout(function() {
                    alert.remove();
                }, 500);
            });
        }, 5000);
    </script>
    
    <?php if (isset($customScripts)): ?>
        <?= $customScripts ?>
    <?php endif; ?>
</body>
</html>
