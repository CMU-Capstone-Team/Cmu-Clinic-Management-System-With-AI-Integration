<?php

use App\Http\Controllers\Student\RegisterController;
use App\Http\Controllers\Student\PortalController;
use App\Http\Controllers\Student\MedicalRecordController;
use App\Http\Controllers\Student\EmailOtpController;
use App\Http\Controllers\MedicalExcuseController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ClinicVisitController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\StudentExportController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/login');

// Public routes
Route::middleware('guest')->group(function (): void {
    Route::get('/login', [LoginController::class, 'create'])
        ->name('login');

    Route::post('/login', [LoginController::class, 'store'])
        ->middleware('throttle:6,1')
        ->name('login.store');

    Route::get('/pre-register', [RegisterController::class, 'create'])
        ->name('pre-register');

    Route::post('/pre-register', [RegisterController::class, 'store'])
        ->middleware('throttle:5,1')
        ->name('pre-register.store');

    Route::get('/student/verify-email', [EmailOtpController::class, 'show'])
        ->name('student.otp.show');

    Route::post('/student/verify-email', [EmailOtpController::class, 'verify'])
        ->middleware('throttle:10,1')
        ->name('student.otp.verify');

    Route::post('/student/verify-email/resend', [EmailOtpController::class, 'resend'])
        ->middleware('throttle:3,1')
        ->name('student.otp.resend');
});

// Student portal
Route::middleware([
    'auth:student',
    \App\Http\Middleware\EnsureStudentEmailVerified::class,
])->group(function (): void {
    Route::get('/student/home', function (\Illuminate\Http\Request $request) {
        return view('students.home', [
            'student' => $request->user('student'),
        ]);
    })->name('student.home');

    Route::get('/student/dashboard', function () {
        return redirect()->route('student.home');
    })->name('student.dashboard');

    Route::get('/student/profile', [PortalController::class, 'profile'])
        ->name('student.profile');

    Route::patch('/student/profile/academic', [PortalController::class, 'updateAcademic'])
        ->middleware('throttle:6,1')
        ->name('student.profile.academic');

    Route::patch('/student/profile/email', [PortalController::class, 'updateEmail'])
        ->middleware('throttle:6,1')
        ->name('student.profile.email');

    Route::patch('/student/profile/password', [PortalController::class, 'updatePassword'])
        ->middleware('throttle:6,1')
        ->name('student.profile.password');

    Route::patch('/student/profile/photo', [PortalController::class, 'updatePhoto'])
        ->middleware('throttle:6,1')
        ->name('student.profile.photo');

    Route::get('/student/profile/photo', [PortalController::class, 'photo'])
        ->name('student.profile.photo.show');

    Route::get('/student/visits', [PortalController::class, 'visits'])
        ->name('student.visits');

    Route::get('/student/records', [MedicalRecordController::class, 'index'])
        ->name('student.records');

    Route::patch('/student/records', [MedicalRecordController::class, 'update'])
        ->middleware('throttle:10,1')
        ->name('student.records.update');

    Route::get('/student/status', [PortalController::class, 'profile'])
        ->name('student.status');

    Route::post('/student/logout', [PortalController::class, 'logout'])
        ->name('student.logout');
});

// Staff portal
Route::middleware('auth')->group(function (): void {
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    Route::get('/dashboard/allergies', [DashboardController::class, 'allergies'])
        ->name('dashboard.allergies');

    Route::get('/clinic/search', [StudentController::class, 'searchPatient'])
        ->name('clinic.search');

    // Student print and Excel export
    Route::get('/students/{student}/print', [StudentExportController::class, 'printRecord'])
        ->name('students.print');

    Route::get('/students/{student}/export-excel', [StudentExportController::class, 'excel'])
        ->name('students.export-excel');

    Route::resource('students', StudentController::class)
        ->only(['index', 'create', 'store', 'show']);

    // Clinic visits
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

    // Medical excuses
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

    Route::post('/logout', [LoginController::class, 'destroy'])
        ->name('logout');
});