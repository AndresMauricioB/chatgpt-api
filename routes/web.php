<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\GmailController;
use App\Http\Controllers\WebScrapingController;
use Illuminate\Support\Facades\Route;

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



Route::get('/', function () { return view('welcome');
});


//API ADMIN AUH
Route::post('/api/v1/auth/login', [AuthController::class, 'login']);
Route::get('/api/v1/auth/check', [AuthController::class, 'check']);



// Login Google
Route::get('/google-auth/redirect', [GmailController::class, 'googleRedirect']);
Route::get('/google-auth/callback', [GmailController::class, 'googleCallback']);



Route::post('/chat/create', [\App\Http\Controllers\chat\create::class, 'create']);
Route::get('/chat/index', [\App\Http\Controllers\chat\create::class, 'listar']);
Route::post('/chat/{chat}/message/show', [\App\Http\Controllers\chat\create::class, 'show']);
Route::delete('/chat/{chat}/message/delete', [\App\Http\Controllers\chat\create::class, 'delete']);
Route::post('/chat/{chat}/message/send', [\App\Http\Controllers\chat\sendMsg::class, 'send']);

Route::get('/user', [\App\Http\Controllers\chat\create::class, 'user']);

Route::middleware([ 'auth:sanctum', config('jetstream.auth_session'),'verified',])->group(function () {
    Route::get('/dashboard', function () {return view('dashboard'); })->name('dashboard');
    Route::get('/chat', function () { return view('chat');})->name('chat')->middleware('admin');
});



// Web scraping
Route::get('/web-scraping', [WebScrapingController::class, 'webScrapingAll']);
