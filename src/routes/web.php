<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ResendEmailController;
use App\Http\Controllers\CKEditorController;
use App\Http\Controllers\EventController;
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
Route::post('/resendemail/temp', [ResendEmailController::class, 'resendTempEmail'])->name('resendtemp');
Route::get('/verified', [PageController::class, 'getPage'])->name('verified');

Route::group(['middleware' => ['auth', 'verified']], function () {
    Route::get('/mytickets',[UserController::class,'myTickets'])->name('mytickets');
    Route::get('/myprizes',[UserController::class,'myPrizes'])->name('myprizes');

    Route::get('/dashboard', [PageController::class, 'dashboard'])->name('dashboard');
    Route::get('/profile',[UserController::class,'index'])->name('profile.index');
    Route::get('/profile/edit',[UserController::class,'edit'])->name('profile.edit');
    Route::post('/profile/update',[UserController::class,'update'])->name('profile.update');

    Route::get('/events',[EventController::class,'index'])->name('event.index');
    Route::get('/event/create',[EventController::class,'create'])->name('event.create');
    Route::get('/event/edit/{id}',[EventController::class,'edit'])->name('event.edit');
    Route::get('/event/myevents',[EventController::class,'myEvents'])->name('event.myevents');

    Route::post('/event/store',[EventController::class,'store'])->name('event.store');
    Route::post('/event/update/{id}',[EventController::class,'update'])->name('event.update');

    Route::post('ckeditor/upload', [CKEditorController::class,'upload'])->name('ckeditor.image-upload');


    Route::get('/event/ticket/{id}',[EventController::class,'showTicketForm'])->name('event.ticket');

    Route::post('/event/addticket',[EventController::class,'addTicket'])->name('event.addticket');

    Route::post('/event/color',[EventController::class,'showTicketColorForm'])->name('event.color');
    Route::post('/event/addcolor',[EventController::class,'addTicketColor'])->name('event.addcolor');

    Route::post('/event/number',[EventController::class,'showTicketNumberForm'])->name('event.number');

    Route::get('/event/{id}',[EventController::class,'show'])->name('event.show')->where('id', '[0-9]+');
});

Route::get('/event/landing/{hash}', [EventController::class, 'showByHash'])->name('event.show.hash');

Route::get('/dashboard/{hash}', [PageController::class, 'dashboardTemp'])->name('dashboardtemp');


Route::get('/qrcoderegister', [RegisterController::class, 'showTempRegistrationForm'])->name('qrcodereg');
Route::post('/qrcoderegister', [RegisterController::class, 'registerTemp'])->name('registerTemp');


Route::get('/terms-and-conditions', [PageController::class, 'getPage'])->name('terms-and-conditions');

Route::get('initmigration', function () {

    \Artisan::call('migrate:fresh');

    dd("Migration succeded!");

});

