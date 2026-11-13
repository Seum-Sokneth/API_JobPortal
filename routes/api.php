<?php

use App\Http\Controllers\CompanyController;
use App\Http\Controllers\JobDepartementController;
use App\Http\Controllers\JobPostController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/job-departements', [JobDepartementController::class, 'index']);
Route::post('/job-departements', [JobDepartementController::class, 'store']);
Route::get('/job-departements/{id}', [JobDepartementController::class, 'show']);
Route::put('/job-departements/{id}', [JobDepartementController::class, 'update']);
Route::delete('/job-departements/{id}', [JobDepartementController::class, 'destroy']);

Route::get('/job-posts', [JobPostController::class, 'index']);
Route::post('/job-posts', [JobPostController::class, 'store']);
Route::get('/job-posts/{id}', [JobPostController::class, 'show']);
Route::put('/job-posts/{id}', [JobPostController::class, 'update']);
Route::delete('/job-posts/{id}', [JobPostController::class, 'destroy']);
Route::get('/job-posts/search', [JobPostController::class, 'search']);

Route::get('/companies', [CompanyController::class, 'index']);
Route::post('/companies', [CompanyController::class, 'store']);
Route::get('/companies/{id}', [CompanyController::class, 'show']);
Route::put('/companies/{id}', [CompanyController::class, 'update']);
Route::delete('/companies/{id}', [CompanyController::class, 'destroy']);
