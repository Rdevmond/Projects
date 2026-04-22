<?php
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ExamController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// Public Home
Route::get('/', function () {return view('welcome');})->name('home');

// Authentication
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ADMIN ONLY ROUTES
Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [ExamController::class, 'index'])->name('admin.dashboard');
    Route::get('/create-exam', [ExamController::class, 'create'])->name('admin.soal');
    Route::post('/save-exam', [ExamController::class, 'store'])->name('admin.exams.store');

    // --- TAMBAHAN FITUR DISINI ---
    // Route untuk ganti status Active/Inactive
    Route::patch('/exams/{exam}/toggle-status', [ExamController::class, 'toggleStatus'])->name('admin.exams.toggle-status');
    // Route untuk detail spek/leaderboard per ujian
    Route::get('/exams/{exam}/specs', [ExamController::class, 'showSpecs'])->name('admin.specs');
    // ----------------------------
 
    Route::get('/leaderboard/{exam}', [ExamController::class, 'leaderboard'])->name('admin.leaderboard');
    // Welcome Page CMS
    Route::get('/welcome-settings', [App\Http\Controllers\Admin\WelcomeSettingsController::class, 'index'])->name('admin.welcome.settings');
    Route::post('/welcome-settings', [App\Http\Controllers\Admin\WelcomeSettingsController::class, 'update'])->name('admin.welcome.settings.update');
    
    // Achievements CMS
    Route::resource('achievements', App\Http\Controllers\Admin\AchievementController::class)->names([
        'index' => 'admin.achievements.index',
        'create' => 'admin.achievements.create',
        'store' => 'admin.achievements.store',
        'edit' => 'admin.achievements.edit',
        'update' => 'admin.achievements.update',
        'destroy' => 'admin.achievements.destroy',
    ]);
    Route::get('/users', [UserController::class, 'index'])->name('admin.users.index');
    Route::post('/users', [UserController::class, 'store'])->name('admin.users.store');
    Route::get('/users/template', [UserController::class, 'downloadTemplate'])->name('admin.users.template');
    Route::post('/users/import', [UserController::class, 'import'])->name('admin.users.import');
    Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('admin.users.destroy');
    Route::get('/users/{id}/edit', [UserController::class, 'edit'])->name('admin.users.edit');
    Route::put('/users/{id}', [UserController::class, 'update'])->name('admin.users.update');
    Route::get('/exams/{exam}/submissions', [ExamController::class, 'showSubmissions'])->name('submissions.index');
    Route::get('/submissions/{submission}/review', [ExamController::class, 'reviewSubmission'])->name('submissions.review');
    Route::post('/submissions/{submission}/grade', [ExamController::class, 'gradeEssay'])->name('submissions.grade');
    Route::get('/exams/{exam}/edit', [ExamController::class, 'edit'])->name('admin.exams.edit');
    Route::put('/exams/{exam}', [ExamController::class, 'update'])->name('admin.exams.update');
    Route::delete('/exams/{exam}', [ExamController::class, 'destroy'])->name('admin.exams.destroy');
    Route::delete('/submissions/{submission}', [ExamController::class, 'destroySubmission'])->name('submissions.destroy');
    Route::get('/exams/{exam}/report', [ExamController::class, 'downloadReport'])->name('admin.exams.report');
});

// STUDENT ONLY ROUTES
Route::middleware(['auth', 'student'])->group(function () {
    Route::get('/exam-list', [ExamController::class, 'studentIndex'])->name('student.exams');
    Route::get('/exam/{exam}/confirm', [ExamController::class, 'showConfirmation'])->name('exam.confirm');
    Route::post('/exam/{exam}/start', [ExamController::class, 'startExam'])->name('exam.start');
    Route::post('/exam/{exam}/update-step', [ExamController::class, 'updateStep'])->name('exam.update-step');
    Route::post('/submit-exam/{exam}', [ExamController::class, 'submit'])->name('exam.submit');
    Route::get('/exam-result/{submission}', [ExamController::class, 'showResult'])->name('student.result');
});

// SHARED AUTHENTICATED ROUTES
Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.update-password');
});
