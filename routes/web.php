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
$router->post('/admin/activities/classes/store', [ActivityController::class, 'storeClass']);
$router->post('/admin/activities/classes/{id}/update', [ActivityController::class, 'updateClass']);
$router->post('/admin/activities/classes/{id}/delete', [ActivityController::class, 'deleteClass']);

// Admin activities - Program Tahun Ajaran
$router->post('/admin/activities/programs-tahun/store', [ActivityController::class, 'storeProgramTahun']);
$router->post('/admin/activities/programs-tahun/{id}/update', [ActivityController::class, 'updateProgramTahun']);
$router->post('/admin/activities/programs-tahun/{id}/delete', [ActivityController::class, 'deleteProgramTahun']);

// Admin activities - Program Gallery
$router->post('/admin/activities/programs-tahun/{programId}/gallery/store', [ActivityController::class, 'storeGalleryImage']);
$router->post('/admin/activities/programs-tahun/{programId}/gallery/{galleryId}/update', [ActivityController::class, 'updateGalleryImage']);
$router->post('/admin/activities/programs-tahun/{programId}/gallery/{galleryId}/delete', [ActivityController::class, 'deleteGalleryImage']);

// Admin activities - Update/Delete Program Image via Gallery Modal
$router->post('/admin/activities/programs-tahun/{programId}/update-program-image', [ActivityController::class, 'updateProgramImage']);
$router->post('/admin/activities/programs-tahun/{programId}/delete-program-image', [ActivityController::class, 'deleteProgramImage']);

// Admin activities - Program Harian
$router->post('/admin/activities/programs-harian/{id}/update', [ActivityController::class, 'updateProgramHarian']);

// Admin activities - Program Harian Gallery
$router->post('/admin/activities/programs-harian/{programId}/gallery/store', [ActivityController::class, 'storeGalleryHarianImage']);
$router->post('/admin/activities/programs-harian/{programId}/gallery/{galleryId}/update', [ActivityController::class, 'updateGalleryHarianImage']);
$router->post('/admin/activities/programs-harian/{programId}/gallery/{galleryId}/delete', [ActivityController::class, 'deleteGalleryHarianImage']);

// Admin activities - Update/Delete Program Harian Image via Gallery Modal
$router->post('/admin/activities/programs-harian/{programId}/update-program-image', [ActivityController::class, 'updateProgramHarianImage']);
$router->post('/admin/activities/programs-harian/{programId}/delete-program-image', [ActivityController::class, 'deleteProgramHarianImage']);

// 404 handler
$router->notFound(function() {
    http_response_code(404);
    if (file_exists(VIEW_PATH . '/errors/404.php')) {
        require VIEW_PATH . '/errors/404.php';
    } else {
        echo "<h1>404 - Page Not Found</h1>";
    }
});
