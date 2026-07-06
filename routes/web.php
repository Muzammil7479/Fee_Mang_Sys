<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\AdminStudentController;
use App\Http\Controllers\TeacherController;

// Portal
Route::get('/', function () {
    return view('portal');
})->name('portal');

/*
|--------------------------------------------------------------------------
| Account Section Routes
|--------------------------------------------------------------------------
*/

Route::get('/account-section', [AccountController::class, 'index'])->name('account.dashboard');
Route::post('/account-section/create-class-plan', [AccountController::class, 'createClassPlan'])->name('account.createClassPlan');
Route::post('/account-section/apply-scholarship', [AccountController::class, 'applyScholarship'])->name('account.applyScholarship');
Route::post('/account-section/add-payment', [AccountController::class, 'addPayment'])->name('account.addPayment');
Route::post('/account-section/add-fine', [AccountController::class, 'addFine'])->name('account.addFine');

/*
|--------------------------------------------------------------------------
| Admin Student Admission Routes
|--------------------------------------------------------------------------
*/

Route::get('/admin', [AdminStudentController::class, 'index'])->name('admin.dashboard');
Route::get('/admin/students', [AdminStudentController::class, 'index'])->name('admin.students');
Route::post('/admin/students/store', [AdminStudentController::class, 'store'])->name('admin.students.store');
Route::get('/admin/students/{id}/edit', [AdminStudentController::class, 'edit'])->name('admin.students.edit');
Route::post('/admin/students/{id}/update', [AdminStudentController::class, 'update'])->name('admin.students.update');

/*
|--------------------------------------------------------------------------
| Teacher Management Routes
|--------------------------------------------------------------------------
*/

Route::get('/teacher', [TeacherController::class, 'dashboard'])->name('teacher.dashboard');
Route::get('/teachers', [TeacherController::class, 'index'])->name('teachers.index');
Route::get('/teachers/create', [TeacherController::class, 'create'])->name('teachers.create');
Route::post('/teachers', [TeacherController::class, 'store'])->name('teachers.store');
Route::get('/teachers/{id}', [TeacherController::class, 'show'])->name('teachers.show');
Route::get('/teachers/{id}/edit', [TeacherController::class, 'edit'])->name('teachers.edit');
Route::put('/teachers/{id}', [TeacherController::class, 'update'])->name('teachers.update');
Route::delete('/teachers/{id}', [TeacherController::class, 'destroy'])->name('teachers.destroy');

/*
|--------------------------------------------------------------------------
| Student Section
|--------------------------------------------------------------------------
*/

Route::get('/student', [StudentController::class, 'index'])->name('student.dashboard');

/*
|--------------------------------------------------------------------------
| Principal Section
|--------------------------------------------------------------------------
*/

Route::get('/principal', function () {
    return "Principal Interface";
})->name('principal.dashboard');