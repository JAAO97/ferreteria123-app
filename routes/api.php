<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\EmpleadoController as ApiEmpleadoController;
use App\Http\Controllers\API\ProductoController as ApiProductoController;
use Illuminate\Support\Facades\Route;

// Laravel añade /api automáticamente desde bootstrap/app.php.
Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:5,1');

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::get('/productos', [ApiProductoController::class, 'index'])->middleware('abilities:productos:read');
    Route::get('/productos/{producto}', [ApiProductoController::class, 'show'])->middleware('abilities:productos:read');
    Route::post('/productos', [ApiProductoController::class, 'store'])->middleware('abilities:productos:write');
    Route::match(['put', 'patch'], '/productos/{producto}', [ApiProductoController::class, 'update'])->middleware('abilities:productos:write');
    Route::delete('/productos/{producto}', [ApiProductoController::class, 'destroy'])->middleware('abilities:productos:write');

    Route::get('/empleados', [ApiEmpleadoController::class, 'index'])->middleware('abilities:empleados:read');
    Route::get('/empleados/{empleado}', [ApiEmpleadoController::class, 'show'])->middleware('abilities:empleados:read');
    Route::post('/empleados', [ApiEmpleadoController::class, 'store'])->middleware('abilities:empleados:write');
    Route::match(['put', 'patch'], '/empleados/{empleado}', [ApiEmpleadoController::class, 'update'])->middleware('abilities:empleados:write');
    Route::delete('/empleados/{empleado}', [ApiEmpleadoController::class, 'destroy'])->middleware('abilities:empleados:write');
});
