<?php

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

Route::get('/', function () {
    return view('welcome');
});

Route::post('/chat/create', [\App\Http\Controllers\chat\create::class, 'create']);
Route::get('/chat/index', [\App\Http\Controllers\chat\create::class, 'listar']);
Route::post('/chat/{chat}/message/show', [\App\Http\Controllers\chat\create::class, 'show']);
Route::post('/chat/{chat}/message/send', [\App\Http\Controllers\chat\sendMsg::class, 'send']);