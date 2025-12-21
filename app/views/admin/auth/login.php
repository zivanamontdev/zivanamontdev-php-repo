<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - <?= APP_NAME ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '<?= colors("primary") ?>',
                        'white-neutral': '<?= colors("white_neutral") ?>',
                        'white-shadow': '<?= colors("white_shadow") ?>',
                        'black-soft': '<?= colors("black_soft") ?>',
                    }
                }
            }
        }
    </script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');
        * { font-family: 'Inter', sans-serif; }
        
        /* Decorative circles with gradient blur */
        .gradient-circle {
            position: absolute;
            width: 866px;
            height: 866px;
            border-radius: 50%;
            background: radial-gradient(circle, #C92C2F 0%, #FFDCAA 100%);
            filter: blur(300px);
            pointer-events: none;
            z-index: 0;
        }
        
        .gradient-circle-bottom-left {
            bottom: 0;
            left: 0;
            transform: translate(-75%, 75%);
        }
        
        .gradient-circle-top-right {
            top: 0;
            right: 0;
            transform: translate(75%, -75%);
        }
        
        /* Toast Notification */
        .toast {
            position: fixed;
            top: 24px;
            right: 24px;
            min-width: 300px;
            max-width: 500px;
            padding: 12px 16px;
            border-radius: 12px;
            border: 1px solid;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            display: flex;
            align-items: center;
            gap: 12px;
            z-index: 9999;
            animation: slideInRight 0.3s ease-out;
            transform: translateX(0);
            opacity: 1;
        }
        
        .toast.success {
            background-color: #F0FDF4;
            border-color: #BBF7D0;
            color: #166534;
        }
        
        .toast.error {
            background-color: #FEF2F2;
            border-color: #FECACA;
            color: #991B1B;
        }
        
        .toast-icon {
            flex-shrink: 0;
            width: 20px;
            height: 20px;
        }
        
        .toast.success .toast-icon {
            color: #16A34A;
        }
        
        .toast.error .toast-icon {
            color: #DC2626;
        }
        
        .toast-content {
            flex: 1;
            font-size: 14px;
            font-weight: 500;
        }
        
        .toast-close {
            flex-shrink: 0;
            width: 16px;
            height: 16px;
            cursor: pointer;
            opacity: 0.7;
            transition: opacity 0.2s;
            background: none;
            border: none;
            padding: 0;
        }
        
        .toast-close:hover {
            opacity: 1;
        }
        
        @keyframes slideInRight {
            from {
                transform: translateX(400px);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
        
        @keyframes slideOutRight {
            from {
                transform: translateX(0);
                opacity: 1;
            }
            to {
                transform: translateX(400px);
                opacity: 0;
            }
        }
        
        .toast.hiding {
            animation: slideOutRight 0.3s ease-out forwards;
        }
    </style>
</head>
<body style="background-color: <?= colors('white_secondary') ?>;" class="min-h-screen flex items-center justify-center relative overflow-hidden">
    <!-- Decorative gradient circles -->
    <div class="gradient-circle gradient-circle-bottom-left"></div>
    <div class="gradient-circle gradient-circle-top-right"></div>
    
    <div class="w-full flex flex-col lg:flex-row items-center justify-center lg:justify-between gap-8 lg:gap-12 px-4 sm:px-8 md:px-16 lg:px-24 xl:px-[350px] py-8 lg:py-0 relative z-10">
        <!-- Section 1: Form Login (Left) -->
        <div class="w-full max-w-[400px] lg:flex-shrink-0">
                <img 
                    src="/images/logo.png" 
                    alt="<?= APP_NAME ?>" 
                    class="w-[150px] sm:w-[200px] h-auto mb-[30px] sm:mb-[40px]"
                >
                
                <form action="<?= url('/admin/login') ?>" method="POST">
                    <?= csrf_field() ?>
                    
                    <!-- Email Field -->
                    <?php component('input', [
                        'name' => 'username',
                        'id' => 'username',
                        'type' => 'text',
                        'label' => 'Email',
                        'placeholder' => 'Masukkan email admin',
                        'value' => old('username'),
                        'required' => true
                    ]); ?>
                    
                    <!-- Password Field (20px gap from email) -->
                    <div style="margin-top: 20px;" class="relative">
                        <label for="password" class="block font-normal text-[16px] leading-[28px] mb-[8px]" style="color: <?= colors('black_soft') ?>;">
                            Password
                        </label>
                        <div class="relative">
                            <input 
                                type="password" 
                                id="password" 
                                name="password"
                                placeholder="Masukkan kata sandi admin"
                                required
                                class="w-full bg-white-neutral border border-[#E0E0E0] rounded-[12px] font-normal text-[16px] leading-[28px] placeholder:text-white-soft focus:outline-none focus:border-primary transition-colors px-[16px] py-[12px] pr-[40px]"
                                style="background-color: <?= colors('white_neutral') ?>; color: <?= colors('black_soft') ?>;"
                            >
                            <!-- Eye Icon Toggle -->
                            <button type="button" id="toggle-password" class="absolute right-[16px] top-1/2 -translate-y-1/2 transition-colors" style="color: <?= colors('white_soft') ?>;">
                                <svg id="eye-icon" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                                <svg id="eye-off-icon" class="w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                    
                    <!-- Remember Me & Forgot Password (20px gap from password) -->
                    <div style="margin-top: 20px;" class="flex items-center justify-between">
                        <div class="flex items-center">
                            <input 
                                type="checkbox" 
                                id="remember" 
                                name="remember" 
                                value="1" 
                                class="w-3 h-3 border-gray-300 rounded"
                                style="margin-right: 8px; accent-color: <?= colors('primary') ?>;"
                            >
                            <label for="remember" class="font-normal text-[16px] leading-[28px]" style="color: <?= colors('white_shadow') ?>;">
                                Ingat saya
                            </label>
                        </div>
                        <a href="<?= url('/admin/forget-password') ?>" class="font-normal text-[16px] leading-[28px]" style="color: <?= colors('primary') ?>;">
                            Lupa kata sandi
                        </a>
                    </div>
                    
                    <!-- Login Button (32px gap from checkbox) -->
                    <div style="margin-top: 32px;">
                        <?php component('button', [
                            'text' => 'Masuk',
                            'variant' => '1',
                            'type' => 'submit',
                            'class' => 'w-full'
                        ]); ?>
                    </div>
                </form>
        </div>
        
        <!-- Section 2: Image (Right) -->
        <div class="hidden lg:block">
            <img 
                src="/images/image_profile_section_1.png" 
                alt="Login Illustration" 
                class="rounded-[16px] w-full max-w-[500px] xl:max-w-[650px] h-auto lg:h-[500px] xl:h-[647px] object-cover"
            >
        </div>
    </div>
    
    <script>
        // Show toast notification
        function showToast(message, type = 'success') {
            // Create toast element
            const toast = document.createElement('div');
            toast.className = `toast ${type}`;
            
            // Success icon SVG (matching dashboard style)
            const successIcon = `
                <svg class="toast-icon" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M10 18C14.4183 18 18 14.4183 18 10C18 5.58172 14.4183 2 10 2C5.58172 2 2 5.58172 2 10C2 14.4183 5.58172 18 10 18Z" stroke="currentColor" stroke-width="1.5"/>
                    <path d="M7 10L9 12L13 8" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            `;
            
            // Error icon SVG (matching dashboard style)
            const errorIcon = `
                <svg class="toast-icon" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M10 18C14.4183 18 18 14.4183 18 10C18 5.58172 14.4183 2 10 2C5.58172 2 2 5.58172 2 10C2 14.4183 5.58172 18 10 18Z" stroke="currentColor" stroke-width="1.5"/>
                    <path d="M12.5 7.5L7.5 12.5M7.5 7.5L12.5 12.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                </svg>
            `;
            
            // Close icon SVG (matching dashboard style)
            const closeIcon = `
                <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M12 4L4 12M4 4L12 12" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                </svg>
            `;
            
            toast.innerHTML = `
                ${type === 'success' ? successIcon : errorIcon}
                <span class="toast-content">${message}</span>
                <button type="button" class="toast-close">${closeIcon}</button>
            `;
            
            document.body.appendChild(toast);
            
            // Add click event to close button
            const closeBtn = toast.querySelector('.toast-close');
            closeBtn.addEventListener('click', () => {
                closeToast(toast);
            });
            
            // Auto close after 4 seconds
            setTimeout(() => {
                closeToast(toast);
            }, 4000);
        }
        
        function closeToast(toastElement) {
            if (!toastElement || !toastElement.parentNode) return;
            toastElement.classList.add('hiding');
            setTimeout(() => {
                if (toastElement.parentNode) {
                    toastElement.remove();
                }
            }, 300);
        }
        
        // Show toast on page load if there's a flash message
        document.addEventListener('DOMContentLoaded', function() {
            <?php 
            // Get flash messages directly from session to avoid double reading
            $successMsg = isset($_SESSION['flash']['success']) ? $_SESSION['flash']['success'] : null;
            $errorMsg = isset($_SESSION['flash']['error']) ? $_SESSION['flash']['error'] : null;
            
            // Clear flash after reading
            if ($successMsg) unset($_SESSION['flash']['success']);
            if ($errorMsg) unset($_SESSION['flash']['error']);
            ?>
            
            <?php if ($successMsg): ?>
                console.log('Success flash:', <?= json_encode($successMsg) ?>);
                showToast(<?= json_encode($successMsg) ?>, 'success');
            <?php endif; ?>
            
            <?php if ($errorMsg): ?>
                console.log('Error flash:', <?= json_encode($errorMsg) ?>);
                showToast(<?= json_encode($errorMsg) ?>, 'error');
            <?php endif; ?>
        });
        
        // Toggle password visibility
        document.getElementById('toggle-password').addEventListener('click', function() {
            const passwordInput = document.getElementById('password');
            const eyeIcon = document.getElementById('eye-icon');
            const eyeOffIcon = document.getElementById('eye-off-icon');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.classList.add('hidden');
                eyeOffIcon.classList.remove('hidden');
            } else {
                passwordInput.type = 'password';
                eyeIcon.classList.remove('hidden');
                eyeOffIcon.classList.add('hidden');
            }
        });
    </script>
</body>
</html>
