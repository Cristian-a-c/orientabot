<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChatController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/estudiantes', function () {
    return view('estudiantes.interaccion');
});

Route::post('/api/chat/send', [ChatController::class, 'sendMessage']);
