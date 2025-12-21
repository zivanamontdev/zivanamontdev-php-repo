<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Kata Sandi - <?= APP_NAME ?></title>
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
    
    <!-- Card Container -->
    <div class="w-full max-w-[500px] mx-auto px-4 sm:px-6 md:px-8 relative z-10">
        <!-- White Card -->
        <div class="bg-white-pure rounded-[16px] px-[24px] py-[32px] shadow-lg">
            <!-- Title -->
            <h1 class="text-center font-bold text-[20px] leading-[100%] mb-[40px]" style="color: <?= colors('black_highlight') ?>;">
                Lupa Kata Sandi
            </h1>
            
            <!-- Form -->
            <form action="<?= url('/admin/forget-password') ?>" method="POST">
                <?= csrf_field() ?>
                
                <!-- Email Label -->
                <label for="email" class="block font-normal text-[16px] leading-[28px] mb-[8px]" style="color: <?= colors('black_soft') ?>;">
                    Email
                </label>
                
                <!-- Email Input -->
                <?php component('input', [
                    'name' => 'email',
                    'id' => 'email',
                    'type' => 'email',
                    'placeholder' => 'Masukkan email admin kembali',
                    'value' => old('email'),
                    'required' => true
                ]); ?>
                
                <!-- Spacer -->
                <div class="mb-[32px]"></div>
                
                <!-- Submit Button -->
                <?php component('button', [
                    'text' => 'Kirimkan Email Reset',
                    'variant' => '1',
                    'type' => 'submit',
                    'class' => 'w-full'
                ]); ?>
                
                <!-- Spacer -->
                <div class="mb-[40px]"></div>
                
                <!-- Information Text -->
                <p class="text-center font-normal text-[12px] leading-[21px]" style="color: <?= colors('white_shadow') ?>;">
                    Kamu akan menerima link reset kata sandi setelah mengonfirmasi kembali email admin yang terdaftar. Pastikan untuk memasukkan email yang benar.
                </p>
            </form>
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
    </script>
</body>
</html>
