<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsuariosController;
use App\Http\Controllers\TrabajosController;
use App\Http\Controllers\PrendasController;
use App\Http\Controllers\NotificacionesController;
use App\Http\Controllers\InventarioController;
use App\Http\Controllers\FacturasController;
use App\Http\Controllers\CalendarioController;
use App\Http\Middleware\Cors;

Route::post('/usuarios/login', [UsuariosController::class, 'login']);

Route::middleware(['auth:sanctum'])->group(function () {


    Route::get('/usuarios', [UsuariosController::class, 'index'])
        ->middleware('role:admin');

    Route::get('/usuarios/{id}', [UsuariosController::class, 'show']);

    Route::post('/usuarios', [UsuariosController::class, 'store'])
        ->middleware('role:admin');

    Route::put('/usuarios/{id}', [UsuariosController::class, 'update']);

    Route::delete('/usuarios/{id}', [UsuariosController::class, 'destroy'])
        ->middleware('role:admin');


    Route::get('/trabajos', [TrabajosController::class, 'index']);
    Route::get('/trabajos/{id}', [TrabajosController::class, 'show']);

    Route::post('/trabajos', [TrabajosController::class, 'store'])
        ->middleware('role:empleado,cliente');

    Route::put('/trabajos/{id}', [TrabajosController::class, 'update'])
        ->middleware('role:admin,empleado');

    Route::delete('/trabajos/{id}', [TrabajosController::class, 'destroy'])
        ->middleware('role:admin');

    Route::get('/prendas', [PrendasController::class, 'index'])
        ->middleware('role:admin,empleado');

    Route::post('/prendas', [PrendasController::class, 'store'])
        ->middleware('role:admin,empleado');

    Route::put('/prendas/{id}', [PrendasController::class, 'update'])
        ->middleware('role:admin,empleado');

    Route::delete('/prendas/{id}', [PrendasController::class, 'destroy'])
        ->middleware('role:admin');

    Route::get('/inventario', [InventarioController::class, 'index'])
        ->middleware('role:admin,empleado');

    Route::post('/inventario', [InventarioController::class, 'store'])
        ->middleware('role:admin');

    Route::put('/inventario/{id}', [InventarioController::class, 'update'])
        ->middleware('role:admin,empleado');

    Route::delete('/inventario/{id}', [InventarioController::class, 'destroy'])
        ->middleware('role:admin');

    Route::get('/facturas', [FacturasController::class, 'index']);

    Route::post('/facturas', [FacturasController::class, 'store'])
        ->middleware('role:admin,empleado');

    Route::put('/facturas/{id}', [FacturasController::class, 'update'])
        ->middleware('role:admin');

    Route::delete('/facturas/{id}', [FacturasController::class, 'destroy'])
        ->middleware('role:admin');

    Route::get('/notificaciones', [NotificacionesController::class, 'index']);

    Route::post('/notificaciones', [NotificacionesController::class, 'store'])
        ->middleware('role:admin,empleado');

    Route::get('/eventos', [CalendarioController::class, 'index']);

    Route::post('/eventos', [CalendarioController::class, 'store'])
        ->middleware('role:admin,empleado,cliente');

});