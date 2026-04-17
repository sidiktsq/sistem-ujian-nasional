<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Admin\UserController as AdminUserController;

use App\Http\Controllers\Admin\ProfileController as AdminProfileController;
use App\Http\Controllers\Guru\DashboardController as GuruDashboard;
use App\Http\Controllers\Guru\ExamController as GuruExamController;
use App\Http\Controllers\Guru\QuestionController as GuruQuestionController;
use App\Http\Controllers\Guru\ResultController as GuruResultController;
use App\Http\Controllers\Guru\ProfileController as GuruProfile;
use App\Http\Controllers\Murid\DashboardController as MuridDashboard;
use App\Http\Controllers\Murid\ExamController as MuridExamController;
use App\Http\Controllers\Murid\ResultController as MuridResultController;
use App\Http\Controllers\Murid\ProfileController as MuridProfile;
use Illuminate\Support\Facades\Route;

// Redirect root ke login
Route::get('/', function () {
    if (auth()->check()) {
        $user = auth()->user();
        return $user->role === 'admin' 
            ? redirect()->route('admin.dashboard')
            : ($user->isGuru() ? redirect()->route('guru.dashboard') : redirect()->route('murid.dashboard'));
    }
    return redirect()->route('login');
});

// ─── Auth Routes ───────────────────────────────────────────
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    
    // Google Authentication
    Route::get('/auth/google', [GoogleController::class, 'redirect'])->name('auth.google');
    Route::get('/auth/google/callback', [GoogleController::class, 'callback'])->name('auth.google.callback');
    Route::get('/auth/google/complete', [GoogleController::class, 'complete'])->name('auth.google.complete');
    Route::post('/auth/google/complete', [GoogleController::class, 'storeComplete'])->name('auth.google.complete.store');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// ─── Admin Routes ────────────────────────────────────────────
Route::prefix('admin')
    ->middleware(['auth', 'role:admin'])
    ->name('admin.')
    ->group(function () {
    
    // Dashboard
    Route::get('/dashboard', [AdminDashboard::class, 'index'])->name('dashboard');
    
    // Users Management
    Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
    Route::get('/users/{user}/edit', [AdminUserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}', [AdminUserController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [AdminUserController::class, 'destroy'])->name('users.destroy');
    Route::post('/users/{user}/toggle-role', [AdminUserController::class, 'toggleRole'])->name('users.toggle-role');
    

    
    // Profile
    Route::get('/profile', [AdminProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [AdminProfileController::class, 'edit'])->name('profile.edit');
    Route::get('/profile/settings', [AdminProfileController::class, 'settings'])->name('profile.settings');
    Route::post('/profile', [AdminProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/upload-avatar', [AdminProfileController::class, 'uploadAvatar'])->name('profile.upload-avatar');
    Route::post('/profile/remove-avatar', [AdminProfileController::class, 'removeAvatar'])->name('profile.remove-avatar');
    Route::delete('/profile', [AdminProfileController::class, 'deleteAccount'])->name('profile.delete');
});

// ─── Guru Routes ────────────────────────────────────────────
Route::prefix('guru')
    ->middleware(['auth', 'role:guru'])
    ->name('guru.')
    ->group(function () {

    // Dashboard
    Route::get('/dashboard', [GuruDashboard::class, 'index'])->name('dashboard');

    // Profile
    Route::get('/profile', [GuruProfile::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [GuruProfile::class, 'edit'])->name('profile.edit');
    Route::get('/profile/settings', [GuruProfile::class, 'settings'])->name('profile.settings');
    Route::post('/profile', [GuruProfile::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [GuruProfile::class, 'updatePassword'])->name('profile.change-password');
    Route::post('/profile/upload-avatar', [GuruProfile::class, 'uploadAvatar'])->name('profile.upload-avatar');
    Route::post('/profile/remove-avatar', [GuruProfile::class, 'removeAvatar'])->name('profile.remove-avatar');
    Route::delete('/profile', [GuruProfile::class, 'deleteAccount'])->name('profile.delete');

    // Ujian (Exam) CRUD
    Route::resource('exams', GuruExamController::class);

    // Soal (Question) CRUD — nested di dalam exam
    Route::prefix('exams/{exam}/questions')
        ->name('exams.questions.')
        ->group(function () {
            Route::get('/', [GuruQuestionController::class, 'index'])->name('index');
            Route::get('/create', [GuruQuestionController::class, 'create'])->name('create');
            Route::post('/', [GuruQuestionController::class, 'store'])->name('store');
            Route::get('/bulk-create', [GuruQuestionController::class, 'bulkCreate'])->name('bulk-create');
            Route::post('/bulk-store', [GuruQuestionController::class, 'bulkStore'])->name('bulk-store');
            Route::get('/{question}/edit', [GuruQuestionController::class, 'edit'])->name('edit');
            Route::put('/{question}', [GuruQuestionController::class, 'update'])->name('update');
            Route::delete('/{question}', [GuruQuestionController::class, 'destroy'])->name('destroy');
        });

    // Hasil Ujian
    Route::get('exams/{exam}/results', [GuruResultController::class, 'index'])->name('exams.results.index');
    Route::get('exams/{exam}/results/{session}', [GuruResultController::class, 'show'])->name('exams.results.show');
    Route::post('exams/{exam}/results/{session}/confirm', [GuruResultController::class, 'confirmResults'])->name('exams.results.confirm');
    Route::post('exams/{exam}/results/{session}/grade-essay/{answer}', [GuruResultController::class, 'gradeEssay'])->name('exams.results.grade-essay');
});

// ─── Murid Routes ────────────────────────────────────────────
Route::prefix('murid')
    ->middleware(['auth', 'role:murid'])
    ->name('murid.')
    ->group(function () {

    // Dashboard
    Route::get('/dashboard', [MuridDashboard::class, 'index'])->name('dashboard');

    // Profile
    Route::get('/profile', [MuridProfile::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [MuridProfile::class, 'edit'])->name('profile.edit');
    Route::get('/profile/settings', [MuridProfile::class, 'settings'])->name('profile.settings');
    Route::post('/profile', [MuridProfile::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [MuridProfile::class, 'updatePassword'])->name('profile.change-password');
    Route::post('/profile/upload-avatar', [MuridProfile::class, 'uploadAvatar'])->name('profile.upload-avatar');
    Route::post('/profile/remove-avatar', [MuridProfile::class, 'removeAvatar'])->name('profile.remove-avatar');
    Route::delete('/profile', [MuridProfile::class, 'deleteAccount'])->name('profile.delete');

    // Ujian
    Route::get('/exams', [MuridExamController::class, 'index'])->name('exams.index');
    Route::get('/exams/{exam}', [MuridExamController::class, 'show'])->name('exams.show');
    Route::post('/exams/{exam}/start', [MuridExamController::class, 'start'])->name('exams.start');
    Route::post('/exams/{exam}/sessions/{session}/submit', [MuridExamController::class, 'submit'])->name('exams.submit');

    // Hasil
    Route::get('/results', [MuridResultController::class, 'index'])->name('results.index');
    Route::get('/results/{session}', [MuridResultController::class, 'show'])->name('results.show');
});
