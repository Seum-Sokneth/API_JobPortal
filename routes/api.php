
<?php

use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmployerController;
use App\Http\Controllers\JobDepartementController;
use App\Http\Controllers\JobPostController;
use App\Http\Controllers\ResumeController;
use App\Http\Controllers\SaveJobController;
use App\Http\Controllers\SeekersController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// ========================================
// 1. PUBLIC ROUTES
// ========================================

// Authentication
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login'])
    ->middleware('throttle:5,1');

// Public job posts
Route::get('/job-posts', [JobPostController::class, 'index']);
Route::get('/job-posts/search', [JobPostController::class, 'search']);
Route::get('/job-posts/{id}', [JobPostController::class, 'show']);

// Public companies
Route::get('/companies', [CompanyController::class, 'index']);
Route::get('/companies/{id}', [CompanyController::class, 'show']);

// ========================================
// 2. AUTHENTICATED ROUTES
// ========================================

Route::middleware('auth:sanctum')->group(function () {

    // Authentication
    Route::post('/logout', [AuthController::class, 'logout']);

    // Dashboard
    Route::get('/dashboard', DashboardController::class);

    // Routes shared by multiple roles must be registered once to avoid overwriting middleware.
    Route::middleware('role:admin,employer')->group(function () {
        Route::post('/companies', [CompanyController::class, 'store']);
        Route::put('/companies/{id}', [CompanyController::class, 'update']);
    });

    Route::middleware('role:admin,seeker')->group(function () {
        Route::get('/seekers', [SeekersController::class, 'index']);
        Route::get('/seekers/{id}', [SeekersController::class, 'show']);
        Route::put('/seekers/{id}', [SeekersController::class, 'update']);
    });

    Route::middleware('role:employer,seeker')->group(function () {
        Route::get('/applications', [ApplicationController::class, 'index']);
        Route::get('/applications/{id}', [ApplicationController::class, 'show']);
    });

    // ====================================
    // 3. ADMIN ROUTES
    // ====================================

    Route::middleware('role:admin')->group(function () {

        // User Management
        Route::get('/users', [UserController::class, 'index']);
        Route::get('/users/{id}', [UserController::class, 'show']);
        Route::delete('/users/{id}', [UserController::class, 'destroy']);

        // Job Department Management
        Route::get('/job-departements', [JobDepartementController::class, 'index']);
        Route::post('/job-departements', [JobDepartementController::class, 'store']);
        Route::get('/job-departements/{id}', [JobDepartementController::class, 'show']);
        Route::put('/job-departements/{id}', [JobDepartementController::class, 'update']);
        Route::delete('/job-departements/{id}', [JobDepartementController::class, 'destroy']);

        // Approve Job Posts
        Route::get('/admin/job-posts/pending', [JobPostController::class, 'pendingForAdmin']);
        Route::patch('/admin/job-posts/{jobPost}/approve', [JobPostController::class, 'approve']);

        // Company Management
        Route::delete('/companies/{id}', [CompanyController::class, 'destroy']);

        // Seeker Management
        Route::get('/seekers/search', [SeekersController::class, 'search']);
        Route::delete('/seekers/{id}', [SeekersController::class, 'destroy']);

    });

    // ====================================
    // 4. EMPLOYER ROUTES
    // ====================================

    Route::middleware('role:employer')->group(function () {

        // Job Post Management
        Route::post('/job-posts', [JobPostController::class, 'store']);
        Route::put('/job-posts/{id}', [JobPostController::class, 'update']);
        Route::delete('/job-posts/{id}', [JobPostController::class, 'destroy']);

        // Company Management
        // Employer Profile
        Route::get('/employer', [EmployerController::class, 'index']);
        Route::post('/employer', [EmployerController::class, 'store']);
        Route::get('/employer/{id}', [EmployerController::class, 'show']);
        Route::put('/employer/{id}', [EmployerController::class, 'update']);

        Route::get('/job-departements', [JobDepartementController::class, 'index']);
        Route::post('/job-departements', [JobDepartementController::class, 'store']);
        Route::get('/job-departements/{id}', [JobDepartementController::class, 'show']);
        Route::put('/job-departements/{id}', [JobDepartementController::class, 'update']);
        Route::delete('/job-departements/{id}', [JobDepartementController::class, 'destroy']);

        // Applications Management
        Route::put('/applications/{id}', [ApplicationController::class, 'update']);

    });

    // ====================================
    // 5. SEEKER ROUTES
    // ====================================

    Route::middleware('role:seeker')->group(function () {

        // Seeker profile routes are shared with admins above.
        Route::post('/seekers', [SeekersController::class, 'store']);

        // Resume Management
        Route::get('/resume', [ResumeController::class, 'index']);
        Route::post('/resume', [ResumeController::class, 'store']);
        Route::get('/resume/{id}', [ResumeController::class, 'show']);
        Route::put('/resume/{id}', [ResumeController::class, 'update']);
        Route::delete('/resume/{id}', [ResumeController::class, 'destroy']);

        // Job Applications
        Route::post('/applications', [ApplicationController::class, 'store']);

        // Saved Jobs
        Route::get('/savejob', [SaveJobController::class, 'index']);
        Route::post('/savejob', [SaveJobController::class, 'store']);
        Route::get('/savejob/{id}', [SaveJobController::class, 'show']);
        Route::delete('/savejob/{id}', [SaveJobController::class, 'destroy']);

    });

});
