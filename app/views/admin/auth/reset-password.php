<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Kata Sandi - <?= APP_NAME ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '<?= colors("primary") ?>',
                        'white-neutral': '<?= colors("white_neutral") ?>',
                        'white-pure': '<?= colors("white_pure") ?>',
                        'white-shadow': '<?= colors("white_shadow") ?>',
                        'white-soft': '<?= colors("white_soft") ?>',
                        'black-soft': '<?= colors("black_soft") ?>',
                        'black-highlight': '<?= colors("black_highlight") ?>',
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
    
    <div class="w-full flex flex-col lg:flex-row items-center justify-center lg:justify-between gap-8 lg:gap-12 px-4 sm:px-8 md:px-16 lg:px-24 xl:px-[500px] py-8 lg:py-0 relative z-10">
        <!-- Logo (Left Side) -->
        <div class="hidden lg:block lg:flex-shrink-0">
            <img 
                src="/images/logo.png" 
                alt="<?= APP_NAME ?>" 
                class="w-[200px] h-[73px]"
            >
        </div>
        
        <!-- Card Container (Right Side) -->
        <div class="w-full max-w-[418px] lg:flex-shrink-0">
            <!-- White Card -->
            <div class="bg-white-pure rounded-[16px] px-[24px] py-[32px] shadow-lg">
                <!-- Title -->
                <h1 class="text-left font-bold text-[20px] leading-[100%] mb-[40px]" style="color: <?= colors('black_highlight') ?>;">
                    Reset Kata Sandi
                </h1>
                
                <!-- Form -->
                <form action="<?= url('/admin/reset-password') ?>" method="POST">
                    <?= csrf_field() ?>
                    
                    <!-- Hidden Token Field -->
                    <input type="hidden" name="token" value="<?= e($token ?? $_GET['token'] ?? '') ?>">
                    
                    <!-- Password Field -->
                    <div class="mb-[20px]">
                        <label for="password" class="block font-normal text-[16px] leading-[28px] mb-[8px]" style="color: <?= colors('black_soft') ?>;">
                            Kata Sandi
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
                    
                    <!-- Confirm Password Field -->
                    <div>
                        <label for="confirm_password" class="block font-normal text-[16px] leading-[28px] mb-[8px]" style="color: <?= colors('black_soft') ?>;">
                            Ulangi Kata Sandi
                        </label>
                        <div class="relative">
                            <input 
                                type="password" 
                                id="confirm_password" 
                                name="confirm_password"
                                placeholder="Masukkan ulang kata sandi admin"
                                required
                                class="w-full bg-white-neutral border border-[#E0E0E0] rounded-[12px] font-normal text-[16px] leading-[28px] placeholder:text-white-soft focus:outline-none focus:border-primary transition-colors px-[16px] py-[12px] pr-[40px]"
                                style="background-color: <?= colors('white_neutral') ?>; color: <?= colors('black_soft') ?>;"
                            >
                            <!-- Eye Icon Toggle -->
                            <button type="button" id="toggle-confirm-password" class="absolute right-[16px] top-1/2 -translate-y-1/2 transition-colors" style="color: <?= colors('white_soft') ?>;">
                                <svg id="eye-icon-confirm" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                                <svg id="eye-off-icon-confirm" class="w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                    
                    <!-- Spacer -->
                    <div class="mb-[44px]"></div>
                    
                    <!-- Submit Button -->
                    <?php component('button', [
                        'text' => 'Reset Kata Sandi',
                        'variant' => '1',
                        'type' => 'submit',
                        'class' => 'w-full'
                    ]); ?>
                </form>
            </div>
        </div>
    </div>
    
    <script>
        // Show toast notification
        function showToast(message, type = 'success') {
            const toast = document.createElement('div');
            toast.className = `toast ${type}`;
            
            const successIcon = `
                <svg class="toast-icon" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M10 18C14.4183 18 18 14.4183 18 10C18 5.58172 14.4183 2 10 2C5.58172 2 2 5.58172 2 10C2 14.4183 5.58172 18 10 18Z" stroke="currentColor" stroke-width="1.5"/>
                    <path d="M7 10L9 12L13 8" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            `;
            
            const errorIcon = `
                <svg class="toast-icon" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M10 18C14.4183 18 18 14.4183 18 10C18 5.58172 14.4183 2 10 2C5.58172 2 2 5.58172 2 10C2 14.4183 5.58172 18 10 18Z" stroke="currentColor" stroke-width="1.5"/>
                    <path d="M12.5 7.5L7.5 12.5M7.5 7.5L12.5 12.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                </svg>
            `;
            
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
            
            const closeBtn = toast.querySelector('.toast-close');
            closeBtn.addEventListener('click', () => {
                closeToast(toast);
            });
            
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
            $successMsg = isset($_SESSION['flash']['success']) ? $_SESSION['flash']['success'] : null;
            $errorMsg = isset($_SESSION['flash']['error']) ? $_SESSION['flash']['error'] : null;
            
            if ($successMsg) unset($_SESSION['flash']['success']);
            if ($errorMsg) unset($_SESSION['flash']['error']);
            ?>
            
            <?php if ($successMsg): ?>
                showToast(<?= json_encode($successMsg) ?>, 'success');
            <?php endif; ?>
            
            <?php if ($errorMsg): ?>
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
        
        // Toggle confirm password visibility
        document.getElementById('toggle-confirm-password').addEventListener('click', function() {
            const confirmPasswordInput = document.getElementById('confirm_password');
            const eyeIconConfirm = document.getElementById('eye-icon-confirm');
            const eyeOffIconConfirm = document.getElementById('eye-off-icon-confirm');
            
            if (confirmPasswordInput.type === 'password') {
                confirmPasswordInput.type = 'text';
                eyeIconConfirm.classList.add('hidden');
                eyeOffIconConfirm.classList.remove('hidden');
            } else {
                confirmPasswordInput.type = 'password';
                eyeIconConfirm.classList.remove('hidden');
                eyeOffIconConfirm.classList.add('hidden');
            }
        });
    </script>
</body>
</html>
