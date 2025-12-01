<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

/*
Route::get('/estudiantes', function () {
    return view('estudiantes.interaccion');
});*/

Route::view('/estudiantes', 'estudiantes.interaccion');

