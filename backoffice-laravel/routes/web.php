<?php

use App\Http\Controllers\ChartController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TrainerController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

Route::get('/add-new-user', function () {
    return view('newUser');
});

Route::get('/add-new-client', function () {
    return view('clients-views/create');
});

Route::get('/dashboard', [ChartController::class, 'index'])
    ->middleware(['auth'])
    ->name('dashboard');

Route::get('/trainer-dash', [TrainerController::class, 'index'])
    ->middleware(['auth'])
    ->name('trainer-dash');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
