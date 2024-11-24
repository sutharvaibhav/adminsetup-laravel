<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\FrontController;

//Admin routes
Route::get('/admin',[AuthController::class,'admin'])->name('admin');

Route::middleware(['guest'])->group(function (){
    // Route::get('/login',[AuthController::class,'loginForm'])->name('login');
    Route::post('/login',[AuthController::class,'login'])->name('login.submit');
    // Route::get('/signup',[AuthController::class,'signupForm'])->name('signup');
    Route::post('/signup',[AuthController::class,'signup'])->name('signup.submit');
});

Route::middleware(['auth.custom'])->prefix('admin/')->group(function (){
    Route::get('/dashboard',[AuthController::class,'dashboard'])->name('dashboard');
    Route::post('/change-password',[AuthController::class,'changepassword'])->name('admin.changepassword');
    Route::get('/logout',[AuthController::class,'logout'])->name('admin.logout');
});

//Front routes
Route::get('/',[FrontController::class,'front'])->name('home.frontend');
