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

// Todas las rutas de API con middleware CORS
Route::middleware(Cors::class)->group(function () {

    // -------------------- Usuarios --------------------
    Route::prefix('usuarios')->group(function () {
        Route::post('/login', [UsuariosController::class, 'checkPassword']); // Login
        Route::get('/', [UsuariosController::class, 'index']); // Listar usuarios
        Route::get('/{id}', [UsuariosController::class, 'show']); // Ver usuario por ID
        Route::post('/', [UsuariosController::class, 'store']); // Crear usuario
        Route::put('/{id}', [UsuariosController::class, 'update']); // Actualizar usuario
        Route::put('/{id}/password', [UsuariosController::class, 'updatePassword']); // Actualizar contraseña
        Route::delete('/{id}', [UsuariosController::class, 'destroy']); // Eliminar usuario
    });

    // -------------------- Trabajos --------------------
    Route::prefix('trabajos')->group(function () {
        Route::get('/', [TrabajosController::class, 'index']);
        Route::get('/{id}', [TrabajosController::class, 'show']);
        Route::post('/', [TrabajosController::class, 'store']);
        Route::put('/{id}', [TrabajosController::class, 'update']);
        Route::delete('/{id}', [TrabajosController::class, 'destroy']);
        Route::get('/{id}/consumos', [TrabajosController::class, 'consumos']);
        Route::post('/{id}/consumos', [TrabajosController::class, 'asociarConsumo']);
    });

    // -------------------- Prendas --------------------
    Route::prefix('prendas')->group(function () {
        Route::get('/', [PrendasController::class, 'index']);
        Route::get('/{id}', [PrendasController::class, 'show']);
        Route::post('/', [PrendasController::class, 'store']);
        Route::put('/{id}', [PrendasController::class, 'update']);
        Route::delete('/{id}', [PrendasController::class, 'destroy']);
    });

    // -------------------- Notificaciones --------------------
    Route::prefix('notificaciones')->group(function () {
        Route::get('/', [NotificacionesController::class, 'index']);
        Route::get('/{id}', [NotificacionesController::class, 'show']);
        Route::post('/', [NotificacionesController::class, 'store']);
        Route::put('/{id}', [NotificacionesController::class, 'update']);
        Route::delete('/{id}', [NotificacionesController::class, 'destroy']);
    });

    // -------------------- Inventario --------------------
    Route::prefix('inventario')->group(function () {
        Route::get('/', [InventarioController::class, 'index']);
        Route::get('/bajo-stock', [InventarioController::class, 'bajoStock']);
        Route::get('/{id}', [InventarioController::class, 'show']);
        Route::post('/', [InventarioController::class, 'store']);
        Route::put('/{id}', [InventarioController::class, 'update']);
        Route::delete('/{id}', [InventarioController::class, 'destroy']);
    });

    // -------------------- Facturas --------------------
    Route::prefix('facturas')->group(function () {
        Route::get('/', [FacturasController::class, 'index']);
        Route::get('/{id}', [FacturasController::class, 'show']);
        Route::post('/', [FacturasController::class, 'store']);
        Route::put('/{id}', [FacturasController::class, 'update']);
        Route::delete('/{id}', [FacturasController::class, 'destroy']);
        Route::post('/{id}/asociar-trabajo', [FacturasController::class, 'asociarTrabajo']);
        Route::post('/{id}/desasociar-trabajo', [FacturasController::class, 'desasociarTrabajo']);
        Route::get('/{id}/calcular-total', [FacturasController::class, 'calcularTotal']);
    });

    // -------------------- Calendario / Eventos --------------------
    Route::prefix('eventos')->group(function () {
        Route::get('/', [CalendarioController::class, 'index']);
        Route::get('/{id}', [CalendarioController::class, 'show']);
        Route::post('/', [CalendarioController::class, 'store']);
        Route::put('/{id}', [CalendarioController::class, 'update']);
        Route::delete('/{id}', [CalendarioController::class, 'destroy']);
    });

    // -------------------- CORS Preflight --------------------
    Route::options('{any}', function () {
        return response()->json([], 200);
    })->where('any', '.*');

});