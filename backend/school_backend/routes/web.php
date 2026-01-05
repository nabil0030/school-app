<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminLoginController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\AdminStudentController;


Route::get('/admin/login', [AdminLoginController::class, 'showLogin']);
Route::post('/admin/login', [AdminLoginController::class, 'login']);
Route::get('/admin/logout', [AdminLoginController::class, 'logout']);


Route::get('/admin/dashboard', [AdminDashboardController::class, 'index']);

Route::get('/admin/students/{id}', [AdminStudentController::class, 'show']);

Route::post('/admin/students/{id}/accept', [AdminStudentController::class, 'accept']);
Route::post('/admin/students/{id}/reject', [AdminStudentController::class, 'reject']);

