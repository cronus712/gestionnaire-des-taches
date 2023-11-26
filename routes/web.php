<?php

use App\Http\Controllers\ProjectController;
use App\Http\Controllers\SendMailController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Mail\SampleMail;
use Illuminate\Support\Facades\Mail;
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


Route::get('/send-mail', [SendMailController::class, 'index']);

// Route::get('/dashboard', function() {
//     return view('dashboard');
// });
