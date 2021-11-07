<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\auth\ResendEmailController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;
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

Route::get('/', [PageController::class, 'index'])->name('home');

Auth::routes(['verify' => true]);

Route::get('/logout', [LoginController::class, 'logout']);
Route::post('/resendemail', [ResendEmailController::class, 'resend'])->name('resendemail');
Route::get('/verified', [PageController::class, 'getPage'])->name('verified');

Route::group(['middleware' => ['auth', 'verified']], function () {
    Route::get('/dashboard', [PageController::class, 'dashboard'])->name('dashboard');
    Route::get('/profile',[UserController::class,'index'])->name('profile.index');
    Route::get('/profile/edit',[UserController::class,'edit'])->name('profile.edit');
    Route::post('/profile/update',[UserController::class,'update'])->name('profile.update');

});


Route::get('/dashboard/{hash}', [PageController::class, 'dashboardTemp'])->name('dashboardtemp');


Route::get('/qrcoderegister', [RegisterController::class, 'showTempRegistrationForm'])->name('qrcodereg');
Route::post('/qrcoderegister', [RegisterController::class, 'registerTemp'])->name('registerTemp');


Route::get('/terms-and-conditions', [PageController::class, 'getPage'])->name('terms-and-conditions');


