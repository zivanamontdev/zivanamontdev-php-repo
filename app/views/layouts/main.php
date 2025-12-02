<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?= e($settings['school_description'] ?? 'Quality Montessori education for your child') ?>">
    <title><?= isset($pageTitle) ? e($pageTitle) . ' - ' : '' ?><?= e($settings['school_name'] ?? APP_NAME) ?></title>
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>
    
    <!-- Tailwind Custom Colors Config -->
    <?= tailwind_config() ?>
    
    <!-- Google Fonts - Onest -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Onest:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Custom Styles -->
    <style>
        * {
            font-family: 'Onest', sans-serif;
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
        
        .prose img {
            border-radius: 0.5rem;
        }
        
        /* Page content transition */
        #page-content {
            transition: opacity 0.15s ease;
        }
        
        #page-content.loading {
            opacity: 0.5;
        }
    </style>
    
    <?php if (isset($customStyles)): ?>
        <?= $customStyles ?>
    <?php endif; ?>
</head>
<body class="bg-white-secondary">
    <!-- Header Component (rendered once) -->
    <?php component('header', [
        'settings' => $settings ?? [],
        'socialMedia' => $socialMedia ?? []
    ]); ?>
    
    <!-- Main Content Container -->
    <div id="page-content">
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
    </div>
    
    <script>
        // AJAX Navigation - Header stays, only content changes
        (function() {
            const pageContent = document.getElementById('page-content');
            
            // Handle all internal link clicks
            document.addEventListener('click', function(e) {
                const link = e.target.closest('a');
                
                // Skip if not a link or external/admin link
                if (!link) return;
                if (link.target === '_blank') return;
                if (link.href.includes('/admin')) return;
                if (link.href.includes('#')) return;
                if (!link.href.startsWith(window.location.origin)) return;
                
                e.preventDefault();
                navigateTo(link.href);
            });
            
            // Handle browser back/forward
            window.addEventListener('popstate', function() {
                navigateTo(window.location.href, false);
            });
            
            async function navigateTo(url, pushState = true) {
                // Add loading state
                pageContent.classList.add('loading');
                
                try {
                    const response = await fetch(url, {
                        headers: { 'X-Requested-With': 'XMLHttpRequest' }
                    });
                    const html = await response.text();
                    
                    // Parse the response
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');
                    
                    // Get new content
                    const newContent = doc.getElementById('page-content');
                    const newTitle = doc.querySelector('title');
                    
                    if (newContent) {
                        // Update content
                        pageContent.innerHTML = newContent.innerHTML;
                        
                        // Update title
                        if (newTitle) {
                            document.title = newTitle.textContent;
                        }
                        
                        // Update URL
                        if (pushState) {
                            history.pushState({}, '', url);
                        }
                        
                        // Update active menu
                        updateActiveMenu(url);
                        
                        // Scroll to top
                        window.scrollTo({ top: 0, behavior: 'instant' });
                    }
                } catch (error) {
                    // Fallback to normal navigation on error
                    window.location.href = url;
                }
                
                // Remove loading state
                pageContent.classList.remove('loading');
            }
            
            function updateActiveMenu(url) {
                const path = new URL(url).pathname;
                document.querySelectorAll('nav a').forEach(link => {
                    const linkPath = new URL(link.href).pathname;
                    if (linkPath === path) {
                        link.classList.add('text-primary', 'font-semibold');
                        link.classList.remove('text-black-highlight');
                    } else if (!link.classList.contains('bg-pale-accent')) {
                        link.classList.remove('text-primary', 'font-semibold');
                        link.classList.add('text-black-highlight');
                    }
                });
            }
        })();
        
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
