<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

//ruta para la funcionalidad entidades 

Route::resource('entidades',EntidadController::class);