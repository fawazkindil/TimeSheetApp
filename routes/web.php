<?php

use App\Http\Controllers\EventController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\LogoutController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
})->name('home');
Route::get('/login', function() {
    return redirect('/')->name('login');
});
Route::post('/login', [LoginController::class, 'authenticate']);
Route::post('/logout', [LogoutController::class, 'logout'])->name('logout');
Route::middleware('auth')->group(function() {
    Route::get('/timesheet', function() {
        return view('timesheet');
    })->name('timesheet');

    // task
Route::get('/tasks', [TaskController::class, 'list'])->name('task.list');
Route::get('/tasks/add', [TaskController::class, 'add'])->name('task.add');
Route::post('/tasks/add', [TaskController::class, 'store'])->name('task.store');
Route::post('/tasks/getEditData', [TaskController::class, 'getEditData'])->name('task.getEditData');
Route::post('/tasks/updateTask', [TaskController::class, 'updateTask'])->name('task.updateTask');
Route::post('/tasks/delete', [TaskController::class, 'delete'])->name('task.delete');

    // Event
Route::post('/events/month', [EventController::class, 'getMonthEvents'])->name('event.month');
Route::post('/events/updateLeave', [EventController::class, 'updateLeave'])->name('event.updateLeave');
});
