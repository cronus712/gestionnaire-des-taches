<?php

use App\Mail\SampleMail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\SendMailController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::resource('user', UserController::class)->middleware('isAdmin');
Route::resource('project', ProjectController::class)->middleware('auth');
Route::resource('task', TaskController::class)->middleware('auth');
Route::get('/accessdenied', function() {

return view('errors.accessdenied');
});

Route::get('/mark-as-read', [App\Http\Controllers\TaskController::class,'markAsRead'])->name('mark-as-read')->middleware('auth');
// Route::get('/send-mail', [SendMailController::class, 'index']);

Route::controller(TaskController::class)->group(function () {
    Route::get('/calendar', 'calendarIndex')->name('event.get');
    Route::post('/calendar', 'calendarStore')->name('event.store');
});

Route::prefix('profile')->group(function() {
    Route::get('edit/', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('update/{user}', [App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');

});
// Route::get('/dashboard', function() {
//     return view('dashboard');
// });
