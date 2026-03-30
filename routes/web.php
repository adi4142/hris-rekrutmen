<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\DepartementController;
use App\Http\Controllers\PositionController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\SelectionController;
use App\Http\Controllers\JobAplicantController;
use App\Http\Controllers\JobVacancieController;
use App\Http\Controllers\SelectionApplicantController;
use App\Http\Controllers\JobApplicationController;
use App\Http\Controllers\RecruitmentBatchController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\SuperAdminUserController;
use App\Http\Controllers\HrdDashboardController;
use App\Http\Controllers\ApplicantDashboardController;

// ============================================================================
// PUBLIC ROUTES
// ============================================================================

Route::get('/jobapplicant/create', [JobAplicantController::class, 'create'])->name('jobapplicant.create');
Route::post('/jobapplicant', [JobAplicantController::class, 'store'])->name('jobapplicant.store');

Route::get('/', function () {
    return view('landing');
})->name('home');

Route::get('/lowongan', function () {
    $jobVacancies = \App\JobVacancie::with(['departement', 'position'])
        ->where('status', 'open')->get();
    return view('lowongan', compact('jobVacancies'));
})->name('lowongan');

Route::get('/lowongan/{id}', function ($id) {
    $vacancy = \App\JobVacancie::with([
        'departement',
        'position',
        'batches' => function ($q) {
            $q->where('status', 'active')->orderBy('date', 'asc');
        },
        'batches.stages.selection',
    ])->findOrFail($id);
    return view('lowongan_detail', compact('vacancy'));
})->name('lowongan.detail');

Route::get('/storage/{path}', function ($path) {
    if (strpos($path, '..') !== false) abort(404);
    $filePath = storage_path('app/public/' . $path);
    if (!file_exists($filePath)) abort(404);
    return response()->file($filePath);
})->where('path', '.*');

Route::get('/offering/respond/{application_id}/{response}', [JobApplicationController::class, 'respondOffering'])
    ->name('offering.respond')->middleware('signed');

Route::post('/offering/negotiate/{application_id}', [JobApplicationController::class, 'submitNegotiation'])
    ->name('offering.negotiate')->middleware(['throttle:10,1']);

// ============================================================================
// AUTHENTICATION
// ============================================================================

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// ============================================================================
// FORGOT PASSWORD
// ============================================================================

Route::get('/forgot-password', [ForgotPasswordController::class, 'showForgotForm'])->name('password.forgot');
Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLink'])
    ->name('password.send.link')->middleware('throttle:5,1');
Route::get('/verify-email-reset', [ForgotPasswordController::class, 'verifyEmail'])->name('password.verify.email');
Route::get('/reset-password', [ForgotPasswordController::class, 'showResetForm'])->name('password.reset.form');
Route::post('/reset-password', [ForgotPasswordController::class, 'resetPassword'])->name('password.reset');

// ============================================================================
// AUTH MIDDLEWARE ONLY
// ============================================================================

Route::middleware(['auth'])->group(function () {
    Route::get('/change-password', [\App\Http\Controllers\PasswordChangeController::class, 'showChangeForm'])
        ->name('password.change.form');
    Route::post('/change-password', [\App\Http\Controllers\PasswordChangeController::class, 'updatePassword'])
        ->name('password.change.update');
});

// ============================================================================
// DASHBOARD REDIRECT
// ============================================================================

Route::middleware(['auth', 'role.verified'])->get('/dashboard', function () {
    $user = auth()->user();
    $role = $user->role ? str_replace(' ', '', strtolower($user->role->name)) : null;

    switch ($role) {
        case 'admin':
            return redirect()->route('admin.dashboard');
        case 'hrd':
            return redirect()->route('hrd.dashboard');
        default:
            return redirect('/login')->with('error', 'Role tidak dikenal');
    }
})->name('dashboard');

// ============================================================================
// ADMIN ROUTES (role tertinggi)
// ============================================================================

Route::middleware(['auth', 'role:admin', 'role.verified'])->prefix('admin')->group(function () {

    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');

    // User Management
    Route::get('/users', [SuperAdminUserController::class, 'index'])->name('admin.users.index');
    Route::post('/users', [SuperAdminUserController::class, 'store'])->name('admin.users.store');
    Route::put('/users/{id}', [SuperAdminUserController::class, 'update'])->name('admin.users.update');
    Route::delete('/users/{id}', [SuperAdminUserController::class, 'destroy'])->name('admin.users.destroy');
    Route::post('/users/{id}/reset-password', [SuperAdminUserController::class, 'resetPassword'])->name('admin.users.reset-password');
    Route::post('/users/{id}/toggle-status', [SuperAdminUserController::class, 'toggleStatus'])->name('admin.users.toggle-status');

    // Activity Logs
    Route::get('/logs', [AdminDashboardController::class, 'logs'])->name('admin.logs');
});

// ============================================================================
// HRD ROUTES
// ============================================================================

Route::middleware(['auth', 'role:hrd', 'role.verified'])->prefix('hrd')->group(function () {
    Route::get('/dashboard', [HrdDashboardController::class, 'index'])->name('hrd.dashboard');
});

// ============================================================================
// ADMIN & HRD SHARED ROUTES
// ============================================================================

Route::middleware(['auth', 'role:admin,hrd', 'role.verified'])->group(function () {

    // Role Management
    Route::get('/role', [RoleController::class, 'index'])->name('role.index');
    Route::get('/role/create', [RoleController::class, 'create'])->name('role.create');
    Route::post('/role', [RoleController::class, 'store'])->name('role.store');
    Route::get('/role/{id}/edit', [RoleController::class, 'edit'])->name('role.edit');
    Route::put('/role/{id}', [RoleController::class, 'update'])->name('role.update');
    Route::delete('/role/{id}', [RoleController::class, 'destroy'])->name('role.destroy');

    // User Management (legacy)
    Route::get('/user', [UsersController::class, 'index'])->name('user.index');
    Route::get('/user/create', [UsersController::class, 'create'])->name('user.create');
    Route::post('/user', [UsersController::class, 'store'])->name('user.store');
    Route::get('/user/{id}/edit', [UsersController::class, 'edit'])->name('user.edit');
    Route::put('/user/{id}', [UsersController::class, 'update'])->name('user.update');
    Route::delete('/user/{id}', [UsersController::class, 'destroy'])->name('user.destroy');

    // Departement
    Route::get('/departement', [DepartementController::class, 'index'])->name('departement.index');
    Route::get('/departement/create', [DepartementController::class, 'create'])->name('departement.create');
    Route::post('/departement', [DepartementController::class, 'store'])->name('departement.store');
    Route::get('/departement/{id}/edit', [DepartementController::class, 'edit'])->name('departement.edit');
    Route::put('/departement/{id}', [DepartementController::class, 'update'])->name('departement.update');
    Route::delete('/departement/{id}', [DepartementController::class, 'destroy'])->name('departement.destroy');

    // Position
    Route::get('/position', [PositionController::class, 'index'])->name('position.index');
    Route::get('/position/create', [PositionController::class, 'create'])->name('position.create');
    Route::post('/position', [PositionController::class, 'store'])->name('position.store');
    Route::get('/position/{id}/edit', [PositionController::class, 'edit'])->name('position.edit');
    Route::put('/position/{id}', [PositionController::class, 'update'])->name('position.update');
    Route::delete('/position/{id}', [PositionController::class, 'destroy'])->name('position.destroy');

    // Selection (tahapan seleksi)
    Route::get('/selection', [SelectionController::class, 'index'])->name('selection.index');
    Route::get('/selection/create', [SelectionController::class, 'create'])->name('selection.create');
    Route::post('/selection', [SelectionController::class, 'store'])->name('selection.store');
    Route::get('/selection/{id}/edit', [SelectionController::class, 'edit'])->name('selection.edit');
    Route::put('/selection/{id}', [SelectionController::class, 'update'])->name('selection.update');
    Route::delete('/selection/{id}', [SelectionController::class, 'destroy'])->name('selection.destroy');

    // Job Applicant
    Route::get('/jobapplicant', [JobAplicantController::class, 'index'])->name('jobapplicant.index');
    Route::get('/jobapplicant/{id}/edit', [JobAplicantController::class, 'edit'])->name('jobapplicant.edit');
    Route::put('/jobapplicant/{id}', [JobAplicantController::class, 'update'])->name('jobapplicant.update');
    Route::delete('/jobapplicant/{id}', [JobAplicantController::class, 'destroy'])->name('jobapplicant.destroy');
    Route::get('/jobapplicant/{id}/profile-ajax', [JobAplicantController::class, 'getProfile'])->name('jobapplicant.profile.ajax');
    Route::get('/jobapplicant/{id}/documents-ajax', [JobAplicantController::class, 'getDocuments'])->name('jobapplicant.documents.ajax');
    Route::post('/jobapplicant/cleanup-rejected', [JobAplicantController::class, 'cleanupRejected'])->name('jobapplicant.cleanupRejected');

    // Job Vacancy
    Route::get('/jobvacancie', [JobVacancieController::class, 'index'])->name('jobvacancie.index');
    Route::get('/jobvacancie/create', [JobVacancieController::class, 'create'])->name('jobvacancie.create');
    Route::post('/jobvacancie', [JobVacancieController::class, 'store'])->name('jobvacancie.store');
    Route::get('/jobvacancie/{id}/edit', [JobVacancieController::class, 'edit'])->name('jobvacancie.edit');
    Route::put('/jobvacancie/{id}', [JobVacancieController::class, 'update'])->name('jobvacancie.update');
    Route::delete('/jobvacancie/{id}', [JobVacancieController::class, 'destroy'])->name('jobvacancie.destroy');
    Route::patch('/jobvacancie/{id}/toggle-status', [JobVacancieController::class, 'toggleStatus'])->name('jobvacancie.toggleStatus');

    // Selection Applicant
    Route::get('/selectionapplicant', [SelectionApplicantController::class, 'index'])->name('selectionapplicant.index');
    Route::get('/selectionapplicant/create', [SelectionApplicantController::class, 'create'])->name('selectionapplicant.create');
    Route::post('/selectionapplicant', [SelectionApplicantController::class, 'store'])->name('selectionapplicant.store');
    Route::get('/selectionapplicant/{id}/edit', [SelectionApplicantController::class, 'edit'])->name('selectionapplicant.edit');
    Route::put('/selectionapplicant/{id}', [SelectionApplicantController::class, 'update'])->name('selectionapplicant.update');
    Route::delete('/selectionapplicant/{id}', [SelectionApplicantController::class, 'destroy'])->name('selectionapplicant.destroy');

    // Recruitment Batch
    Route::get('/recruitment-batch', [RecruitmentBatchController::class, 'index'])->name('recruitment-batch.index');
    Route::get('/recruitment-batch/create/{vacancyId}', [RecruitmentBatchController::class, 'create'])->name('recruitment-batch.create');
    Route::post('/recruitment-batch', [RecruitmentBatchController::class, 'store'])->name('recruitment-batch.store');
    Route::get('/recruitment-batch/{id}/edit', [RecruitmentBatchController::class, 'edit'])->name('recruitment-batch.edit');
    Route::put('/recruitment-batch/{id}', [RecruitmentBatchController::class, 'update'])->name('recruitment-batch.update');
    Route::delete('/recruitment-batch/{id}', [RecruitmentBatchController::class, 'destroy'])->name('recruitment-batch.destroy');

    // Job Application
    Route::get('/jobapplication', [JobApplicationController::class, 'manageApplications'])->name('jobapplication.index');
    Route::get('/jobapplication/manage', [JobApplicationController::class, 'manageApplications'])->name('jobapplication.manage');
    Route::post('/jobapplication/batch-process', [JobApplicationController::class, 'batchProcess'])->name('jobapplication.batchProcess');
    Route::post('/jobapplication/assign-batch', [JobApplicationController::class, 'assignBatch'])->name('jobapplication.assignBatch');
    Route::post('/jobapplication/input-score', [JobApplicationController::class, 'inputScore'])->name('jobapplication.inputScore');
    Route::post('/jobapplication/finalize-offering', [JobApplicationController::class, 'finalizeOffering'])->name('jobapplication.finalizeOffering');
    Route::post('/jobapplication/promote-to-offering', [JobApplicationController::class, 'promoteToOffering'])->name('jobapplication.promoteToOffering');
    Route::get('/jobapplication/offering/preview/{id}', [JobApplicationController::class, 'previewOfferingLetter'])->name('jobapplication.previewOffering');
    Route::post('/jobapplication/offering/approve', [JobApplicationController::class, 'approveOffering'])->name('jobapplication.approveOffering');
    Route::post('/jobapplication/fail-selection', [JobApplicationController::class, 'failSelection'])->name('jobapplication.failSelection');
    Route::post('/jobapplication/respond-negotiation', [JobApplicationController::class, 'respondNegotiation'])->name('jobapplication.respondNegotiation');
    Route::get('/jobapplication/applicant/{applicantId}', [JobApplicationController::class, 'showApplicantDetails'])->name('jobapplication.applicant');
    Route::get('/jobapplication/{id}', [JobApplicationController::class, 'show'])->name('jobapplication.show');
    Route::get('/jobapplication/documents/{id}', [JobApplicationController::class, 'getDocuments'])->name('jobapplication.documents.ajax');
    Route::put('/jobapplication/{id}/status', [JobApplicationController::class, 'updateStatus'])->name('jobapplication.updateStatus');
    Route::post('/jobapplication/{id}/add-selection', [JobApplicationController::class, 'addSelectionStage'])->name('jobapplication.addSelection');
    Route::put('/jobapplication/selection/{selectionApplicantId}/update', [JobApplicationController::class, 'updateSelectionStage'])->name('jobapplication.updateSelection');
    Route::delete('/jobapplication/selection/{selectionApplicantId}', [JobApplicationController::class, 'deleteSelectionStage'])->name('jobapplication.deleteSelection');
    Route::post('/job-applications/{id}/send-email-update', [JobApplicationController::class, 'sendSelectionUpdateEmail'])->name('jobapplication.sendEmailUpdate');
    Route::delete('/jobapplication/{id}', [JobApplicationController::class, 'destroy'])->name('jobapplication.destroy');

    // Chatbot
    Route::post('/chat', [\App\Http\Controllers\ChatbotController::class, 'chat'])->name('hrd.chat');
});

// ============================================================================
// APPLICANT / PELAMAR ROUTES
// ============================================================================

Route::middleware(['auth', 'role:pelamar,tamu'])->prefix('applicant')->group(function () {
    Route::get('/dashboard', [ApplicantDashboardController::class, 'index'])->name('applicant.dashboard');
    Route::get('/profile', [ApplicantDashboardController::class, 'profile'])->name('applicant.profile');
    Route::get('/profile/edit', [ApplicantDashboardController::class, 'editProfile'])->name('applicant.profile.edit');
    Route::put('/profile', [ApplicantDashboardController::class, 'updateProfile'])->name('applicant.profile.update');
    Route::get('/vacancies', [ApplicantDashboardController::class, 'vacancies'])->name('applicant.vacancies');
    Route::get('/apply/{vacancies_id}', [ApplicantDashboardController::class, 'applyForm'])->name('applicant.apply');
    Route::post('/apply/{vacancies_id}', [ApplicantDashboardController::class, 'submitApplication'])->name('applicant.apply.submit');
    Route::get('/applications', [ApplicantDashboardController::class, 'applications'])->name('applicant.applications');
    Route::get('/applications/{id}', [ApplicantDashboardController::class, 'applicationDetail'])->name('applicant.application.detail');
    Route::post('/chat', [\App\Http\Controllers\ChatbotController::class, 'chat'])->name('applicant.chat');
    Route::post('/password-change', [\App\Http\Controllers\PasswordChangeController::class, 'changePassword'])->name('applicant.password.change');
});
