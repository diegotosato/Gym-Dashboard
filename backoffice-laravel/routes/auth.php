<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\ChartController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\MedicalCertificateController;
use App\Http\Controllers\NewCredentialController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\SuspensionController;
use App\Http\Controllers\WelcomeMailController;
use App\Http\Controllers\WorkoutExerciseController;
use App\Http\Controllers\WorkoutProgramController;
use App\Http\Controllers\WorkoutSessionController;
use App\Http\Controllers\WorkoutWeekController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    // Route::get('register', [RegisteredUserController::class, 'create'])
    //     ->name('register');

    Route::post('register', [RegisteredUserController::class, 'store'])->name('register');

    Route::get('login', [AuthenticatedSessionController::class, 'create'])
        ->name('login');

    Route::post('login', [AuthenticatedSessionController::class, 'store']);

    Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])
        ->name('password.request');

    Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])
        ->name('password.email');

    Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])
        ->name('password.reset');

    Route::post('reset-password', [NewPasswordController::class, 'store'])
        ->name('password.store');
});

Route::middleware('auth')->group(function () {
    Route::get('verify-email', EmailVerificationPromptController::class)
        ->name('verification.notice');

    Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');

    Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
        ->middleware('throttle:6,1')
        ->name('verification.send');

    Route::get('confirm-password', [ConfirmablePasswordController::class, 'show'])
        ->name('password.confirm');

    Route::post('confirm-password', [ConfirmablePasswordController::class, 'store']);

    Route::put('password', [PasswordController::class, 'update'])->name('password.update');

    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');

    // CLIENTI
    Route::get('client', [ClientController::class, 'index'])->name('index-client');
    Route::post('client', [ClientController::class, 'store'])->name('store-client');
    Route::get('client/{client}', [ClientController::class, 'show'])->name('show-client');
    Route::get('client/{client}/edit', [ClientController::class, 'edit'])->name('edit-client');
    Route::put('client/{client}', [ClientController::class, 'update'])->name('update-client');
    Route::delete('client/{client}', [ClientController::class, 'destroy'])->name('destroy-client');

    // ABBONAMENTI
    Route::get('client/{client}/subscriptions', [SubscriptionController::class, 'show'])->name('show-subscriptions');
    Route::post('client/{client}/store-subscription', [SubscriptionController::class, 'store'])->name('store-subscription');
    Route::get('subscription/{subscription}/edit', [SubscriptionController::class, 'edit'])->name('edit-subscription');
    Route::put('subscription/{subscription}', [SubscriptionController::class, 'update'])->name('update-subscription');
    Route::delete('subscription/{subscription}', [SubscriptionController::class, 'destroy'])->name('destroy-subscription');

    // SOSPENSIONI ABBONAMENTI
    Route::post('suspension/{client}/{lastSub}', [SuspensionController::class, 'store'])->name('store-suspension');
    Route::put('suspension/{suspension}', [SuspensionController::class, 'update'])->name('update-suspension');
    Route::delete('suspension/{suspension}', [SuspensionController::class, 'destroy'])->name('destroy-suspension');

    // CERTIFICATI MEDICI
    Route::get('client/{client}/medical-certificate', [MedicalCertificateController::class, 'show'])->name('show-medical-cert');
    Route::post('client/{client}/medical-certificate', [MedicalCertificateController::class, 'store'])->name('store-medical-cert');
    Route::get('medical-certificate/{medical_certificate}/edit', [MedicalCertificateController::class, 'edit'])->name('edit-medical-cert');
    Route::put('medical-certificate/{medical_certificate}', [MedicalCertificateController::class, 'update'])->name('update-medical-cert');
    Route::delete('medical-certificate/{medical_certificate}', [MedicalCertificateController::class, 'destroy'])->name('destroy-medical-cert');

    // PROGRAMMI
    Route::get('{client}/programs', [WorkoutProgramController::class, 'index'])->prefix('trainer-dash')->name('programs');
    Route::post('{client}/programs', [WorkoutProgramController::class, 'store'])->prefix('trainer-dash')->name('store-program');
    Route::delete('{client}/programs/{program}', [WorkoutProgramController::class, 'destroy'])->prefix('trainer-dash')->name('destroy-program');

    // SETTIMANE
    Route::get('programs/{program}/weeks', [WorkoutWeekController::class, 'index'])->prefix('trainer-dash')->name('weeks');
    Route::post('programs/{program}/weeks', [WorkoutWeekController::class, 'store'])->prefix('trainer-dash')->name('store-week');
    Route::delete('programs/{program}/weeks/{week}', [WorkoutWeekController::class, 'destroy'])->prefix('trainer-dash')->name('destroy-week');

    // SESSIONI DI ALLENAMENTO
    Route::get('weeks/{week}/sessions', [WorkoutSessionController::class, 'index'])->prefix('trainer-dash')->name('sessions');
    Route::post('weeks/{week}/sessions', [WorkoutSessionController::class, 'store'])->prefix('trainer-dash')->name('store-session');
    Route::delete('weeks/{week}/sessions/{session}', [WorkoutSessionController::class, 'destroy'])->prefix('trainer-dash')->name('destroy-session');

    // ESERCIZI
    Route::get('sessions/{session}/exercises', [WorkoutExerciseController::class, 'index'])->prefix('trainer-dash')->name('exercises');
    Route::post('sessions/{session}/exercises', [WorkoutExerciseController::class, 'store'])->prefix('trainer-dash')->name('store-exercise');
    Route::get('sessions/{session}/exercises/{exercise}/edit', [WorkoutExerciseController::class, 'edit'])->prefix('trainer-dash')->name('edit-exercise');
    Route::put('sessions/{session}/exercises/{exercise}', [WorkoutExerciseController::class, 'update'])->prefix('trainer-dash')->name('update-exercise');
    Route::delete('sessions/{session}/exercises/{exercise}', [WorkoutExerciseController::class, 'destroy'])->prefix('trainer-dash')->name('destroy-exercise');

    // CHARTS
    Route::get('charts', [ChartController::class, 'index'])->name('index-charts');

    // Invio nuove credenziali
    Route::post('new-credentials/{client}', [NewCredentialController::class, 'send_new_credentials'])->name('new-credentials');

    // TEST EMAIL
    // Route::get('email', [WelcomeMailController::class, 'index'])->name('index-email');
});
