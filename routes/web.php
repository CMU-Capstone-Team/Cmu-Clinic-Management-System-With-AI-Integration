<?php

use App\Http\Controllers\Student\RegisterController;
use App\Http\Controllers\Student\PortalController;
use App\Http\Controllers\MedicalExcuseController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ClinicVisitController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\StudentController;
use Illuminate\Support\Facades\Route;

// ============================================================================
// ROOT REDIRECT
// ============================================================================
Route::redirect('/', '/login');

// ============================================================================
// GUEST ROUTES (Public - Walang Login Required)
// ============================================================================
Route::middleware('guest')->group(function (): void {
    Route::get('/login', [LoginController::class, 'create'])->name('login');
    Route::post('/login', [LoginController::class, 'store'])->name('login.store');
    
    // PRE-REGISTRATION
    Route::get('/pre-register', [RegisterController::class, 'create'])->name('pre-register');
    Route::post('/pre-register', [RegisterController::class, 'store'])->name('pre-register.store');
});

// ============================================================================
// STUDENT PORTAL ROUTES
// ============================================================================
Route::middleware('auth:student')->group(function (): void {
    Route::get('/student/dashboard', function () {
    return redirect()->route('student.profile');
    })->name('student.dashboard');

    Route::get('/student/profile', [PortalController::class, 'profile'])
        ->name('student.profile');

    Route::patch('/student/profile/email', [PortalController::class, 'updateEmail'])
        ->middleware('throttle:6,1')
        ->name('student.profile.email');

    Route::patch('/student/profile/password', [PortalController::class, 'updatePassword'])
        ->middleware('throttle:6,1')
        ->name('student.profile.password');

    Route::post('/student/logout', [PortalController::class, 'logout'])
        ->name('student.logout');

    Route::get('/student/visits', [PortalController::class, 'visits'])
        ->name('student.visits');

    Route::get('/student/records', [PortalController::class, 'records'])
        ->name('student.records');

    Route::get('/student/status', [PortalController::class, 'checkStatus'])
        ->name('student.status');

    Route::patch('/student/profile/photo', [PortalController::class, 'updatePhoto'])
        ->middleware('throttle:6,1')
        ->name('student.profile.photo');

    Route::get('/student/profile/photo', [PortalController::class, 'photo'])
        ->name('student.profile.photo.show');
});

// ============================================================================
// ADMIN / STAFF ROUTES (Protected by default 'web' Guard)
// ============================================================================
Route::middleware('auth')->group(function (): void {
    
    // Admin Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Quick Patient Search
    Route::get('/clinic/search', [StudentController::class, 'searchPatient'])
        ->name('clinic.search');

    // Students Resource (Admin/Staff Management)
    Route::resource('students', StudentController::class)
        ->only(['index', 'create', 'store', 'show']);

    // APPROVAL ROUTES
    Route::patch('/students/{student}/approve', [StudentController::class, 'approve'])
        ->name('students.approve');
        
    Route::patch('/students/{student}/reject', [StudentController::class, 'reject'])
        ->name('students.reject');

    // Clinic Visits
    Route::get('/clinic-visits', [ClinicVisitController::class, 'index'])
        ->name('clinic-visits.index');
        
    Route::get('/students/{student}/clinic-visits/create', [ClinicVisitController::class, 'create'])
        ->name('clinic-visits.create');
        
    Route::post('/students/{student}/clinic-visits', [ClinicVisitController::class, 'store'])
        ->name('clinic-visits.store');
        
    Route::get('/clinic-visits/{clinicVisit}', [ClinicVisitController::class, 'show'])
        ->name('clinic-visits.show');
        
    Route::patch('/clinic-visits/{clinicVisit}/review', [ClinicVisitController::class, 'review'])
        ->name('clinic-visits.review');

    // Medical Excuses
    Route::get('/medical-excuses', [MedicalExcuseController::class, 'index'])
        ->name('medical-excuses.index');
        
    Route::get('/clinic-visits/{clinicVisit}/medical-excuses/create', [MedicalExcuseController::class, 'create'])
        ->name('medical-excuses.create');
        
    Route::post('/clinic-visits/{clinicVisit}/medical-excuses', [MedicalExcuseController::class, 'store'])
        ->name('medical-excuses.store');
        
    Route::get('/medical-excuses/{medicalExcuse}/print', [MedicalExcuseController::class, 'printView'])
        ->name('medical-excuses.print');
        
    Route::get('/medical-excuses/{medicalExcuse}', [MedicalExcuseController::class, 'show'])
        ->name('medical-excuses.show');

    // Logout (Shared for both Admin and Student)
    Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');
});