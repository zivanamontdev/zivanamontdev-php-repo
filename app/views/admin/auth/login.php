<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - <?= APP_NAME ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');
        * { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-gradient-to-br from-purple-600 to-indigo-600 min-h-screen flex items-center justify-center px-4">
    <div class="max-w-md w-full">
        <div class="bg-white rounded-2xl shadow-2xl p-8">
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-gray-900">Admin Login</h1>
                <p class="text-gray-600 mt-2"><?= APP_NAME ?></p>
            </div>
            
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
            
            <form action="<?= url('/admin/login') ?>" method="POST" class="space-y-6">
                <?= csrf_field() ?>
                
                <div>
                    <label for="username" class="block text-sm font-medium text-gray-700 mb-2">Username or Email</label>
                    <input type="text" id="username" name="username" value="<?= e(old('username')) ?>" required autofocus
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-600 focus:border-transparent">
                </div>
                
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                    <input type="password" id="password" name="password" required
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-600 focus:border-transparent">
                </div>
                
                <div class="flex items-center">
                    <input type="checkbox" id="remember" name="remember" value="1" class="h-4 w-4 text-purple-600 focus:ring-purple-500 border-gray-300 rounded">
                    <label for="remember" class="ml-2 block text-sm text-gray-700">Remember me</label>
                </div>
                
                <button type="submit" class="w-full bg-purple-600 text-white py-3 rounded-lg font-bold text-lg hover:bg-purple-700 transition">
                    Login
                </button>
            </form>
            
            <div class="mt-8 text-center">
                <a href="<?= url('/') ?>" class="text-purple-600 hover:text-purple-700 text-sm">
                    ← Back to Website
                </a>
            </div>
        </div>
        
        <div class="text-center mt-6 text-white text-sm">
            <p>Default credentials: <strong>admin</strong> / <strong>admin123</strong></p>
        </div>
    </div>
</body>
</html>
