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

// El login se quedad public para que se pueda hacer la comprobacion del password
Route::middleware('cors')->group(function () {
    Route::post('/usuarios/login', [UsuariosController::class, 'checkPassword']);
});

// El resto seran las rutas protegidas por los permisos a considerar
Route::middleware(['auth:sanctum', 'cors'])->group(function () {

    // Usuarios
    Route::prefix('usuarios')->group(function () {

        // Solo admin
        Route::get('/', [UsuariosController::class, 'index'])
            ->middleware('role:admin');

        // Admin o ver propio usuario
        Route::get('/{id}', [UsuariosController::class, 'show'])
            ->middleware('role:admin,empleado,cliente');

        // Crear usuario
        Route::post('/', [UsuariosController::class, 'store'])
            ->middleware('role:admin,empleado');

        // Actualizar usuario
        Route::put('/{id}', [UsuariosController::class, 'update'])
            ->middleware('role:admin,cliente');

        // Cambiar contraseña (propia)
        Route::put('/{id}/password', [UsuariosController::class, 'updatePassword'])
            ->middleware('role:admin,empleado,cliente');

        // Eliminar usuario
        Route::delete('/{id}', [UsuariosController::class, 'destroy'])
            ->middleware('role:admin');
    });

    // Trabajos
    Route::prefix('trabajos')->group(function () {

        Route::get('/', [TrabajosController::class, 'index'])
            ->middleware('role:admin,empleado,cliente');

        Route::get('/{id}', [TrabajosController::class, 'show'])
            ->middleware('role:admin,empleado,cliente');

        Route::post('/', [TrabajosController::class, 'store'])
            ->middleware('role:empleado,cliente');

        Route::put('/{id}', [TrabajosController::class, 'update'])
            ->middleware('role:admin,empleado');

        Route::delete('/{id}', [TrabajosController::class, 'destroy'])
            ->middleware('role:admin,empleado');

        Route::get('/{id}/consumos', [TrabajosController::class, 'consumos'])
            ->middleware('role:admin,empleado');

        Route::post('/{id}/consumos', [TrabajosController::class, 'asociarConsumo'])
            ->middleware('role:admin,empleado');
    });

    // Prendas
    Route::prefix('prendas')->group(function () {

        Route::get('/', [PrendasController::class, 'index'])
            ->middleware('role:admin,empleado');

        Route::get('/{id}', [PrendasController::class, 'show'])
            ->middleware('role:admin,empleado,cliente');

        Route::post('/', [PrendasController::class, 'store'])
            ->middleware('role:admin,empleado');

        Route::put('/{id}', [PrendasController::class, 'update'])
            ->middleware('role:admin,empleado');

        Route::delete('/{id}', [PrendasController::class, 'destroy'])
            ->middleware('role:admin');
    });

    // Notificaicones
    Route::prefix('notificaciones')->group(function () {

        Route::get('/', [NotificacionesController::class, 'index'])
            ->middleware('role:admin,empleado,cliente');

        Route::get('/{id}', [NotificacionesController::class, 'show'])
            ->middleware('role:admin,empleado,cliente');

        Route::post('/', [NotificacionesController::class, 'store'])
            ->middleware('role:admin,empleado');

        Route::put('/{id}', [NotificacionesController::class, 'update'])
            ->middleware('role:admin,empleado');

        Route::delete('/{id}', [NotificacionesController::class, 'destroy'])
            ->middleware('role:admin');
    });

    // Inventario
    Route::prefix('inventario')->group(function () {

        Route::get('/', [InventarioController::class, 'index'])
            ->middleware('role:admin,empleado');

        Route::get('/bajo-stock', [InventarioController::class, 'bajoStock'])
            ->middleware('role:admin,empleado');

        Route::get('/{id}', [InventarioController::class, 'show'])
            ->middleware('role:admin,empleado');

        Route::post('/', [InventarioController::class, 'store'])
            ->middleware('role:admin');

        Route::put('/{id}', [InventarioController::class, 'update'])
            ->middleware('role:admin,empleado');

        Route::delete('/{id}', [InventarioController::class, 'destroy'])
            ->middleware('role:admin');
    });

    // Facturas
    Route::prefix('facturas')->group(function () {

        Route::get('/', [FacturasController::class, 'index'])
            ->middleware('role:admin,empleado,cliente');

        Route::get('/{id}', [FacturasController::class, 'show'])
            ->middleware('role:admin,empleado,cliente');

        Route::post('/', [FacturasController::class, 'store'])
            ->middleware('role:admin,empleado');

        Route::put('/{id}', [FacturasController::class, 'update'])
            ->middleware('role:admin,empleado');

        Route::delete('/{id}', [FacturasController::class, 'destroy'])
            ->middleware('role:admin');

        Route::post('/{id}/asociar-trabajo', [FacturasController::class, 'asociarTrabajo'])
            ->middleware('role:admin');

        Route::post('/{id}/desasociar-trabajo', [FacturasController::class, 'desasociarTrabajo'])
            ->middleware('role:admin');

        Route::get('/{id}/calcular-total', [FacturasController::class, 'calcularTotal'])
            ->middleware('role:admin,empleado');
    });

    //Eventos
    Route::prefix('eventos')->group(function () {

        Route::get('/', [CalendarioController::class, 'index'])
            ->middleware('role:admin,empleado,cliente');

        Route::get('/{id}', [CalendarioController::class, 'show'])
            ->middleware('role:admin,empleado,cliente');

        Route::post('/', [CalendarioController::class, 'store'])
            ->middleware('role:empleado,cliente');

        Route::put('/{id}', [CalendarioController::class, 'update'])
            ->middleware('role:admin,empleado');

        Route::delete('/{id}', [CalendarioController::class, 'destroy'])
            ->middleware('role:admin,empleado');
    });

    // Cors
    Route::options('{any}', function () {
        return response()->json([], 200);
    })->where('any', '.*');

});