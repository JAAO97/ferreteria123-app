<?php

use App\Http\Controllers\EmpleadoController;
use App\Http\Controllers\ProductoController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => redirect()->route('productos.index'));
Route::view('/hola2', 'prueba', ['empresa' => 'Ferretería La Bendición24']);
Route::get('/hola', fn () => 'hola mundo programacion web');

// Panel Blade. No utilizar controladores del namespace API aquí.
Route::resource('productos', ProductoController::class)->except(['show']);
Route::resource('empleados', EmpleadoController::class)->except(['show']);
