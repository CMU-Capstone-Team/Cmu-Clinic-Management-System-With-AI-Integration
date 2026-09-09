<?php
use App\Http\Controllers\MedicalExcuseController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ClinicVisitController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\StudentController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/login');

Route::middleware('guest')->group(function (): void {
    Route::get('/login', [
        LoginController::class,
        'create',
    ])->name('login');

    Route::post('/login', [
        LoginController::class,
        'store',
    ])->name('login.store');
});

Route::middleware('auth')->group(function (): void {
    Route::get('/dashboard', [
        DashboardController::class,
        'index',
    ])->name('dashboard');

    Route::resource('students', StudentController::class)
        ->only([
            'index',
            'create',
            'store',
            'show',
        ]);

    Route::get('/clinic-visits', [
        ClinicVisitController::class,
        'index',
    ])->name('clinic-visits.index');

    Route::get('/students/{student}/clinic-visits/create', [
        ClinicVisitController::class,
        'create',
    ])->name('clinic-visits.create');

    Route::post('/students/{student}/clinic-visits', [
        ClinicVisitController::class,
        'store',
    ])->name('clinic-visits.store');

    Route::get('/clinic-visits/{clinicVisit}', [
        ClinicVisitController::class,
        'show',
    ])->name('clinic-visits.show');

    Route::post('/clinic-visits/{clinicVisit}/generate-ai', [
        ClinicVisitController::class,
        'generateAi',
    ])->name('clinic-visits.generate-ai');

    Route::patch('/clinic-visits/{clinicVisit}/review', [
        ClinicVisitController::class,
        'review',
    ])->name('clinic-visits.review');

    Route::post('/logout', [
        LoginController::class,
        'destroy',
    ])->name('logout');
    Route::get('/medical-excuses', [
        MedicalExcuseController::class,
        'index',
    ])->name('medical-excuses.index');
    
    Route::get('/clinic-visits/{clinicVisit}/medical-excuses/create', [
        MedicalExcuseController::class,
        'create',
    ])->name('medical-excuses.create');
    
    Route::post('/clinic-visits/{clinicVisit}/medical-excuses', [
        MedicalExcuseController::class,
        'store',
    ])->name('medical-excuses.store');
    
    Route::get('/medical-excuses/{medicalExcuse}/print', [
        MedicalExcuseController::class,
        'printView',
    ])->name('medical-excuses.print');
    
    Route::get('/medical-excuses/{medicalExcuse}', [
        MedicalExcuseController::class,
        'show',
    ])->name('medical-excuses.show');
});