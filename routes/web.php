<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\FinesController;
use App\Http\Controllers\LogController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StudentAttendanceController;
use App\Http\Controllers\StudentController;
use App\Models\StudentAttendance;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AdminCodeController;
use App\Http\Controllers\FineController;
use App\Http\Controllers\ImportController;
use App\Http\Resources\Attendance;
use App\Models\User;
use FontLib\Table\Type\name;
use Illuminate\Http\Request as Request;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/admin-code', function () {
    return view('auth.admin-code');
})->name('admin.code');

Route::post('/admin-code', [AdminCodeController::class, 'verify'])->name('admin.code.verify');

Route::get('/register', function () {
    if (!session('admin_code_verified')) {
        return redirect()->route('admin.code');
    }
    return view('auth.register');
})->name('register');

Route::middleware('auth')->group(function () {



    // DASHBOARD RELATED ROUTES
    Route::get('/dashboard', [DashboardController::class, 'viewDashboard'])->name('dashboard');


    // PROFILE RELATED ROUTES
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // LOGS RELATED ROUTES
    Route::get('/logs', [LogController::class, 'viewLogs'])->name('logs');
    Route::post('/logs/export-file', [LogController::class, 'exportFile'])->name('logs.export');
    Route::post('/logs/clear-fines', [LogController::class, 'clearFines'])->name('logs.clear-fines');

    // STUDENT LOGS - API => VIA CATEGORY
    Route::get('/logs/category', [LogController::class, 'filterByCategory'])->name('fetchViaCategory');

    // STUDENT RELATED ROUTES
    Route::post('/addStudent', [StudentController::class, 'create'])->name('addStudent');
    Route::get('/students', [StudentController::class, 'view'])->name('students');
    Route::delete('/deleteStudent', [StudentController::class, 'delete'])->name('deleteStudent');
    Route::patch('/updateStudent', [StudentController::class, 'update'])->name('updateStudent');
    Route::patch('/updateManyStudent', [StudentController::class, 'updateMany'])->name('multiStudentEdit');
    Route::delete('/deleteManyStudent', [StudentController::class, 'manyDelete'])->name('multiStudentDelete');

    // STUDENT - API => VIA SEARCHBAR
    Route::get('/students/filter', [StudentController::class, 'filter'])->name('fetchStudent');

    // STUDENT - API => VIA CATEGORY
    Route::get('/students/category', [StudentController::class, 'filterByCategory'])->name('fetchViaCategory');

    // ATTENDANCE RELATED ROUTES
    Route::get('/attendance', [StudentAttendanceController::class, 'view'])->name('attendance');
    Route::post('/student-attendance', [StudentAttendanceController::class, 'recordAttendance'])->name('attendanceStudent');
    Route::get('/studentAttendace/recent', [StudentAttendanceController::class, 'recent'])->name('getAttendanceRecent');
    Route::get('/student_Attendance', [StudentAttendanceController::class, 'formatAttendance'])->name('try');    // Route::post

    // EVENTS RELATED ROUTES
    Route::post('/addEvent', [EventController::class, 'create'])->name('addEvent');
    Route::get('/events', [EventController::class, 'view'])->name('events');
    Route::delete('/deleteEvent', [EventController::class, 'delete'])->name('deleteEvent');
    Route::patch('/updateEvent', [EventController::class, 'update'])->name('updateEvent'); // Add this line
    Route::patch('/events/{event}/complete', [EventController::class, 'completeEvent'])->name('events.complete');
    Route::post('/events/{id}/complete', [EventController::class, 'completeEvent'])->name('events.complete');

    //IMPORT RELATED ROUTES
    // Route::get('/pages/excel-import', [ImportController::class, 'index'])->name('pages.excel-import');
    Route::post('/import-student', [ImportController::class, 'import'])->name('importStudent');

    // Fine Settings Routes
    Route::get('/fines', [FineController::class, 'view'])->name('fines.view');
    Route::put('/fines/settings', [FinesController::class, 'updateSettings'])->name('fines.settings.update');
    // FINES -> API => VIA CATEGORY
    Route::get('/fines/category', [FinesController::class, 'filterByCategory'])->name('fetchViaCategory');
    // FINES -> API => VIA SEARCHBAR
    Route::get('/fines/filter', [FinesController::class, 'filter'])->name('fetchFines');
});


require __DIR__ . '/auth.php';
