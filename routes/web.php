<?php

use App\Http\Controllers\LogoutController;
use App\Http\Controllers\Mobile\AuthCheckController;
use App\Http\Controllers\Mobile\HomeController;
use App\Http\Controllers\Mobile\LoginController;
use App\Http\Controllers\Mobile\NewsController;
use App\Http\Controllers\Mobile\ProfileController;
use App\Http\Controllers\Mobile\RegisterController;
use Illuminate\Support\Facades\Route;

Route::get('/', AuthCheckController::class)->name('mobile.auth-check');
Route::get('/login', LoginController::class)->name('mobile.login');
Route::get('/logout', LogoutController::class)->name('mobile.logout');
Route::get('/register', RegisterController::class)->name('mobile.register');

Route::middleware(['mobile.auth'])->group(function () {
    Route::get('/home', HomeController::class)->name('mobile.home');
    Route::get('/news', NewsController::class)->name('mobile.news');
    Route::get('/news/feed', [NewsController::class, 'fetch'])->name('mobile.news.feed');
    Route::get('/profile', ProfileController::class)->name('mobile.profile');
});
