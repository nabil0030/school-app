<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\SignupController;
use App\Http\Controllers\Auth\LoginController;

use App\Http\Controllers\Auth\ProfileController;
use App\Http\Controllers\Auth\DocumentController;
use App\Http\Controllers\Auth\BacController;
use App\Http\Controllers\Auth\AdminAuthController;
use App\Http\Controllers\StudentStatusController;
use App\Models\Student;

Route::get('/student/{id}', function ($id) {
    return Student::findOrFail($id);
});


Route::post('/admin/login', [AdminAuthController::class, 'login']);


Route::get('/student/status/{id}', [StudentStatusController::class, 'show']);


Route::post('/bac/upload', [BacController::class, 'upload']);
Route::post('/bac/save',   [BacController::class, 'save']);

Route::post('/update-profile', [DocumentController::class, 'updateProfile']);

Route::post('/upload-cin', [DocumentController::class, 'uploadCin']);

Route::post('/upload-documents', [ProfileController::class, 'uploadDocuments']);

Route::post('/upload-documents/{id}', [DocumentController::class, 'upload']);

Route::put('/profile/{id}', [ProfileController::class, 'update']);
Route::post('/signup', [SignupController::class, 'signup']);
Route::post('/login', [LoginController::class, 'login']);
