<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\DepartementController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DivisionController;
use App\Http\Controllers\PositionController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\PayrollController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\SelectionController;
use App\Http\Controllers\JobAplicantController;
use App\Http\Controllers\JobVacancieController;
use App\Http\Controllers\SelectionApplicantController;
use App\Http\Controllers\JobApplicationController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;

// Import Dashboard Controllers untuk setiap role
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\HrdDashboardController;
use App\Http\Controllers\ApplicantDashboardController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| STRUKTUR DASHBOARD BERDASARKAN ROLE:
| - Admin -> /admin/dashboard (AdminDashboardController)
| - HRD -> /hrd/dashboard (HrdDashboardController)
| - Pelamar/Tamu -> /applicant/dashboard (ApplicantDashboardController)
|
| PENTING: Setiap role memiliki route, controller, dan view TERPISAH
|
*/

// ============================================================================
// PUBLIC ROUTES (Tanpa Autentikasi)
// ============================================================================

Route::get('/', function () {
    return view('landing');
});

// Route lowongan publik untuk guest
Route::get('/lowongan', function () {
    $jobVacancies = \App\JobVacancie::with(['departement', 'position'])
                    ->where('status', 'open')
                    ->get();
    return view('lowongan', compact('jobVacancies'));
})->name('lowongan');

// ============================================================================
// AUTHENTICATION ROUTES
// ============================================================================

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Registration Routes
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

// ============================================================================
// ROUTE REDIRECT DASHBOARD (Mengarahkan ke dashboard sesuai role)
// ============================================================================

// Route /dashboard akan redirect ke dashboard sesuai role user
Route::middleware(['auth'])->get('/dashboard', function () {
    $user = auth()->user();
    $userRole = $user->role ? strtolower($user->role->name) : null;
    
    // Redirect ke dashboard sesuai role
    switch ($userRole) {
        case 'admin':
            return redirect()->route('admin.dashboard');
        case 'hrd':
            return redirect()->route('hrd.dashboard');
        case 'pelamar':
        case 'tamu':
            return redirect()->route('applicant.dashboard');
        case 'karyawan':
            return redirect()->route('employee.dashboard');
        default:
            return redirect('/login')->with('error', 'Role tidak dikenal');
    }
})->name('dashboard');

// ============================================================================
// ADMIN ROUTES (Role: admin)
// ============================================================================

Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    
    // Dashboard Admin
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])
        ->name('admin.dashboard');
});

// Routes yang bisa diakses Admin (dengan prefix kosong untuk backward compatibility)
Route::middleware(['auth', 'role:admin'])->group(function () {
    
    // Role Management
    Route::get('/role', [RoleController::class, 'index'])->name('role.index');
    Route::get('/role/create', [RoleController::class, 'create'])->name('role.create');
    Route::post('/role', [RoleController::class, 'store'])->name('role.store');
    Route::get('/role/{id}/edit', [RoleController::class, 'edit'])->name('role.edit');
    Route::put('/role/{id}', [RoleController::class, 'update'])->name('role.update');
    Route::delete('/role/{id}', [RoleController::class, 'destroy'])->name('role.destroy');

    // User Management
    Route::get('/user', [UsersController::class, 'index'])->name('user.index');
    Route::get('/user/create', [UsersController::class, 'create'])->name('user.create');
    Route::post('/user', [UsersController::class, 'store'])->name('user.store');
    Route::get('/user/{id}/edit', [UsersController::class, 'edit'])->name('user.edit');
    Route::put('/user/{id}', [UsersController::class, 'update'])->name('user.update');
    Route::delete('/user/{id}', [UsersController::class, 'destroy'])->name('user.destroy');
});

// ============================================================================
// HRD ROUTES (Role: hrd)
// ============================================================================

Route::middleware(['auth', 'role:hrd'])->prefix('hrd')->group(function () {
    
    // Dashboard HRD
    Route::get('/dashboard', [HrdDashboardController::class, 'index'])
        ->name('hrd.dashboard');
});

// ============================================================================
// ADMIN & HRD SHARED ROUTES (Kedua role bisa akses)
// ============================================================================

Route::middleware(['auth', 'role:admin,hrd'])->group(function () {

    // Division Management
    Route::get('/division', [DivisionController::class, 'index'])->name('division.index');
    Route::get('/division/create', [DivisionController::class, 'create'])->name('division.create');
    Route::post('/division', [DivisionController::class, 'store'])->name('division.store');
    Route::get('/division/{id}/edit', [DivisionController::class, 'edit'])->name('division.edit');
    Route::put('/division/{id}', [DivisionController::class, 'update'])->name('division.update');
    Route::delete('/division/{id}', [DivisionController::class, 'destroy'])->name('division.destroy');

    // Departement Management
    Route::get('/departement', [DepartementController::class, 'index'])->name('departement.index');
    Route::get('/departement/create', [DepartementController::class, 'create'])->name('departement.create');
    Route::post('/departement', [DepartementController::class, 'store'])->name('departement.store');
    Route::get('/departement/{id}/edit', [DepartementController::class, 'edit'])->name('departement.edit');
    Route::put('/departement/{id}', [DepartementController::class, 'update'])->name('departement.update');
    Route::delete('/departement/{id}', [DepartementController::class, 'destroy'])->name('departement.destroy');

    // Position Management
    Route::get('/position', [PositionController::class, 'index'])->name('position.index');
    Route::get('/position/create', [PositionController::class, 'create'])->name('position.create');
    Route::post('/position', [PositionController::class, 'store'])->name('position.store');
    Route::get('/position/{id}/edit', [PositionController::class, 'edit'])->name('position.edit');
    Route::put('/position/{id}', [PositionController::class, 'update'])->name('position.update');
    Route::delete('/position/{id}', [PositionController::class, 'destroy'])->name('position.destroy');

    // Payroll Management
    Route::get('/payroll', [PayrollController::class, 'index'])->name('payroll.index');
    Route::get('/payroll/create', [PayrollController::class, 'create'])->name('payroll.create');
    Route::post('/payroll', [PayrollController::class, 'store'])->name('payroll.store');
    Route::get('/payroll/{id}', [PayrollController::class, 'show'])->name('payroll.show');
    Route::get('/payroll/{id}/edit', [PayrollController::class, 'edit'])->name('payroll.edit');
    Route::put('/payroll/{id}', [PayrollController::class, 'update'])->name('payroll.update');
    Route::delete('/payroll/{id}', [PayrollController::class, 'destroy'])->name('payroll.destroy');

    // Employee Management
    Route::get('/employee', [EmployeeController::class, 'index'])->name('employee.index');
    Route::get('/employee/create', [EmployeeController::class, 'create'])->name('employee.create');
    Route::post('/employee', [EmployeeController::class, 'store'])->name('employee.store');
    Route::get('/employee/{nip}/edit', [EmployeeController::class, 'edit'])->name('employee.edit');
    Route::put('/employee/{nip}', [EmployeeController::class, 'update'])->name('employee.update');
    Route::delete('/employee/{nip}', [EmployeeController::class, 'destroy'])->name('employee.destroy');
    Route::get('/get-user-email/{id}', [EmployeeController::class, 'getUserEmail']);

    // Selection Management
    Route::get('/selection', [SelectionController::class, 'index'])->name('selection.index');
    Route::get('/selection/create', [SelectionController::class, 'create'])->name('selection.create');
    Route::post('/selection', [SelectionController::class, 'store'])->name('selection.store');
    Route::get('/selection/{id}/edit', [SelectionController::class, 'edit'])->name('selection.edit');
    Route::put('/selection/{id}', [SelectionController::class, 'update'])->name('selection.update');
    Route::delete('/selection/{id}', [SelectionController::class, 'destroy'])->name('selection.destroy');

    // Job Applicant Management
    Route::get('/jobapplicant', [JobAplicantController::class, 'index'])->name('jobapplicant.index');
    Route::get('/jobapplicant/create', [JobAplicantController::class, 'create'])->name('jobapplicant.create');
    Route::post('/jobapplicant', [JobAplicantController::class, 'store'])->name('jobapplicant.store');
    Route::get('/jobapplicant/{id}/edit', [JobAplicantController::class, 'edit'])->name('jobapplicant.edit');
    Route::put('/jobapplicant/{id}', [JobAplicantController::class, 'update'])->name('jobapplicant.update');
    Route::delete('/jobapplicant/{id}', [JobAplicantController::class, 'destroy'])->name('jobapplicant.destroy');

    // Job Vacancy Management
    Route::get('/jobvacancie', [JobVacancieController::class, 'index'])->name('jobvacancie.index');
    Route::get('/jobvacancie/create', [JobVacancieController::class, 'create'])->name('jobvacancie.create');
    Route::post('/jobvacancie', [JobVacancieController::class, 'store'])->name('jobvacancie.store');
    Route::get('/jobvacancie/{id}/edit', [JobVacancieController::class, 'edit'])->name('jobvacancie.edit');
    Route::put('/jobvacancie/{id}', [JobVacancieController::class, 'update'])->name('jobvacancie.update');
    Route::delete('/jobvacancie/{id}', [JobVacancieController::class, 'destroy'])->name('jobvacancie.destroy');

    // Selection Applicant Management
    Route::get('/selectionapplicant', [SelectionApplicantController::class, 'index'])->name('selectionapplicant.index');
    Route::get('/selectionapplicant/create', [SelectionApplicantController::class, 'create'])->name('selectionapplicant.create');
    Route::post('/selectionapplicant', [SelectionApplicantController::class, 'store'])->name('selectionapplicant.store');
    Route::get('/selectionapplicant/{id}/edit', [SelectionApplicantController::class, 'edit'])->name('selectionapplicant.edit');
    Route::put('/selectionapplicant/{id}', [SelectionApplicantController::class, 'update'])->name('selectionapplicant.update');
    Route::delete('/selectionapplicant/{id}', [SelectionApplicantController::class, 'destroy'])->name('selectionapplicant.destroy');

    // Job Application Management
    Route::get('/jobapplication', [JobApplicationController::class, 'index'])->name('jobapplication.index');
    Route::put('/jobapplication/{id}', [JobApplicationController::class, 'update'])->name('jobapplication.update');
    Route::delete('/jobapplication/{id}', [JobApplicationController::class, 'destroy'])->name('jobapplication.destroy');

    // Attendance Management
    Route::get('/attendance', [AttendanceController::class, 'index'])->name('attendance.index');
    Route::get('/attendance/scan', [AttendanceController::class, 'scan'])->name('attendance.scan');
    Route::post('/attendance/store', [AttendanceController::class, 'store'])->name('attendance.store');
});

// ============================================================================
// APPLICANT/PELAMAR ROUTES (Role: pelamar, tamu)
// ============================================================================

Route::middleware(['auth', 'role:pelamar,tamu'])->prefix('applicant')->group(function () {
    
    // Dashboard Pelamar
    Route::get('/dashboard', [ApplicantDashboardController::class, 'index'])
        ->name('applicant.dashboard');
    
    // Profil Pelamar
    Route::get('/profile', [ApplicantDashboardController::class, 'profile'])
        ->name('applicant.profile');
    Route::put('/profile', [ApplicantDashboardController::class, 'updateProfile'])
        ->name('applicant.profile.update');
    
    // Daftar Lowongan Tersedia
    Route::get('/vacancies', [ApplicantDashboardController::class, 'vacancies'])
        ->name('applicant.vacancies');
    
    // Lamar Pekerjaan
    Route::get('/apply/{vacancies_id}', [ApplicantDashboardController::class, 'applyForm'])
        ->name('applicant.apply');
    Route::post('/apply/{vacancies_id}', [ApplicantDashboardController::class, 'submitApplication'])
        ->name('applicant.apply.submit');
    
    // Riwayat Lamaran
    Route::get('/applications', [ApplicantDashboardController::class, 'applications'])
        ->name('applicant.applications');
    Route::get('/applications/{id}', [ApplicantDashboardController::class, 'applicationDetail'])
        ->name('applicant.application.detail');
    
    // Chatbot HR Assistant AI
    Route::get('/chatbot', [\App\Http\Controllers\ChatbotController::class, 'index'])
        ->name('applicant.chatbot');
    Route::post('/chat', [\App\Http\Controllers\ChatbotController::class, 'chat'])
        ->name('applicant.chat');
});

// ============================================================================
// EMPLOYEE/KARYAWAN ROUTES (Role: karyawan)
// ============================================================================

Route::middleware(['auth', 'role:karyawan'])->prefix('employee')->group(function () {
    
    // Dashboard Karyawan (placeholder - gunakan AttendanceController atau buat baru)
    Route::get('/dashboard', [AttendanceController::class, 'employeeDashboard'])
        ->name('employee.dashboard');
});
