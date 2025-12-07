<?php
/**
 * Web Routes
 */

// Public routes
$router->get('/', [HomeController::class, 'index']);
$router->get('/activities', [HomeController::class, 'activities']);
$router->get('/activities-gallery', [HomeController::class, 'activitiesGallery']);
$router->get('/profile', [HomeController::class, 'profile']);
$router->get('/profile-gallery', [HomeController::class, 'profileGallery']);
$router->get('/articles', [HomeController::class, 'articles']);
$router->get('/article-detail', [HomeController::class, 'articleDetail']);
$router->get('/article/{slug}', [HomeController::class, 'article']);
$router->get('/registration', [HomeController::class, 'registration']);
$router->post('/registration', [HomeController::class, 'submitRegistration']);

// Auth routes
$router->get('/admin/login', [AuthController::class, 'showLogin']);
$router->post('/admin/login', [AuthController::class, 'login']);
$router->any('/admin/logout', [AuthController::class, 'logout']);

// Admin dashboard
$router->get('/admin', function() {
    Router::redirect('/admin/dashboard');
});
$router->get('/admin/dashboard', [DashboardController::class, 'index']);

// Admin programs
$router->get('/admin/programs', [ProgramController::class, 'index']);
$router->get('/admin/programs/create', [ProgramController::class, 'create']);
$router->post('/admin/programs/store', [ProgramController::class, 'store']);
$router->get('/admin/programs/{id}/edit', [ProgramController::class, 'edit']);
$router->post('/admin/programs/{id}/update', [ProgramController::class, 'update']);
$router->post('/admin/programs/{id}/delete', [ProgramController::class, 'delete']);
$router->post('/admin/programs/{id}/upload-image', [ProgramController::class, 'uploadImage']);
$router->post('/admin/programs/{programId}/delete-image/{imageId}', [ProgramController::class, 'deleteImage']);

// Admin articles
$router->get('/admin/articles', [ArticleController::class, 'index']);
$router->get('/admin/articles/create', [ArticleController::class, 'create']);
$router->post('/admin/articles/store', [ArticleController::class, 'store']);
$router->get('/admin/articles/{id}/edit', [ArticleController::class, 'edit']);
$router->post('/admin/articles/{id}/update', [ArticleController::class, 'update']);
$router->post('/admin/articles/{id}/delete', [ArticleController::class, 'delete']);

// Admin employees
$router->get('/admin/employees', [EmployeeController::class, 'index']);
$router->get('/admin/employees/create', [EmployeeController::class, 'create']);
$router->post('/admin/employees/store', [EmployeeController::class, 'store']);
$router->get('/admin/employees/{id}/edit', [EmployeeController::class, 'edit']);
$router->post('/admin/employees/{id}/update', [EmployeeController::class, 'update']);
$router->post('/admin/employees/{id}/delete', [EmployeeController::class, 'delete']);

// Admin management
$router->get('/admin/management/schedules', [ManagementController::class, 'schedules']);
$router->post('/admin/management/schedules/create', [ManagementController::class, 'createSchedule']);
$router->post('/admin/management/schedules/{id}/update', [ManagementController::class, 'updateSchedule']);
$router->post('/admin/management/schedules/{id}/delete', [ManagementController::class, 'deleteSchedule']);

$router->get('/admin/management/awards', [ManagementController::class, 'awards']);
$router->post('/admin/management/awards/create', [ManagementController::class, 'createAward']);
$router->post('/admin/management/awards/{id}/update', [ManagementController::class, 'updateAward']);
$router->post('/admin/management/awards/{id}/delete', [ManagementController::class, 'deleteAward']);

$router->get('/admin/management/social-media', [ManagementController::class, 'socialMedia']);
$router->post('/admin/management/social-media/create', [ManagementController::class, 'createSocialMedia']);
$router->post('/admin/management/social-media/{id}/update', [ManagementController::class, 'updateSocialMedia']);
$router->post('/admin/management/social-media/{id}/delete', [ManagementController::class, 'deleteSocialMedia']);

$router->get('/admin/management/settings', [ManagementController::class, 'settings']);
$router->post('/admin/management/settings/update', [ManagementController::class, 'updateSettings']);

// Admin registrations
$router->get('/admin/registrations', [RegistrationController::class, 'index']);
$router->get('/admin/registrations/{id}', [RegistrationController::class, 'show']);
$router->post('/admin/registrations/{id}/delete', [RegistrationController::class, 'delete']);

// Admin activities
$router->get('/admin/activities', [ActivityController::class, 'index']);

// 404 handler
$router->notFound(function() {
    http_response_code(404);
    if (file_exists(VIEW_PATH . '/errors/404.php')) {
        require VIEW_PATH . '/errors/404.php';
    } else {
        echo "<h1>404 - Page Not Found</h1>";
    }
});
