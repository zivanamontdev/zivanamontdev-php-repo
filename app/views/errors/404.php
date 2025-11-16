<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Page Not Found</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <div class="min-h-screen flex items-center justify-center px-4">
        <div class="text-center">
            <h1 class="text-9xl font-bold text-purple-600">404</h1>
            <p class="text-2xl font-semibold text-gray-800 mt-4">Page Not Found</p>
            <p class="text-gray-600 mt-2 mb-8">The page you're looking for doesn't exist or has been moved.</p>
            <a href="<?= url('/') ?>" class="inline-block bg-purple-600 text-white px-8 py-3 rounded-full font-bold hover:bg-purple-700 transition">
                Go Back Home
            </a>
        </div>
    </div>
</body>
</html>
