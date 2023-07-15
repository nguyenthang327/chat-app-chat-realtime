<?php

use App\Http\Controllers\MessageController;
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

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::middleware(['auth'])->group(function(){
    Route::get('/chat', [MessageController::class, 'index'])->name('chat.index');
    Route::get('/conversation', [MessageController::class, 'detail'])->name('chat.conversation.detail');
    Route::post('/sendMsg', [MessageController::class, 'store'])->name('chat.conversation.sendMsg');
});