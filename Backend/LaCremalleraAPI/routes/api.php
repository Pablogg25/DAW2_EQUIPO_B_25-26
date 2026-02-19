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

Route::prefix('api')->middleware(Cors::class)->group(function () {

    // Usuarios
    Route::prefix('usuarios')->group(function () {
        // Listar usuarios
        Route::get('/', [UsuariosController::class, 'index']); // listar con filtro opcional ?username=pablo_adm
        // Obtener usuario por ID
        Route::get('/{id}', [UsuariosController::class, 'show']);
        // Crear usuario
        Route::post('/', [UsuariosController::class, 'store']);
        // Actualizar usuario
        Route::put('/{id}', [UsuariosController::class, 'update']);
        // Actualizar contraseña de un usuario
        Route::put('/{id}/password', [UsuariosController::class, 'updatePassword']);
        // Eliminar usuario por ID
        Route::delete('/{id}', [UsuariosController::class, 'destroy']);
        // Login / verificar contraseña
        Route::post('/login', [UsuariosController::class, 'checkPassword']);
    });

    // Trabajos
    Route::prefix('trabajos')->group(function () {
        // Listar trabajos
        Route::get('/', [TrabajosController::class, 'index']); // puede filtrar por ?empleadoId= ?estado= ?prendaId=
        // Obtener un trabajo por ID
        Route::get('/{id}', [TrabajosController::class, 'show']);
        // Crear nuevo trabajo
        Route::post('/', [TrabajosController::class, 'store']);
        // Actualizar trabajo por ID
        Route::put('/{id}', [TrabajosController::class, 'update']);
        // Eliminar trabajo por ID
        Route::delete('/{id}', [TrabajosController::class, 'destroy']);

        // Consumos de un trabajo
        // Obtener consumos de un trabajo
        Route::get('/{id}/consumos', [TrabajosController::class, 'consumos']);
        // Asociar un consumo a un trabajo
        Route::post('/{id}/consumos', [TrabajosController::class, 'asociarConsumo']);
    });

    // Prendas
    Route::prefix('prendas')->group(function () {
        // Listar todas las prendas
        Route::get('/', [PrendasController::class, 'index']); // filtrar con ?usuarioId=
        // Obtener prendas por Id
        Route::get('/{id}', [PrendasController::class, 'show']);
        // Crear nueva prenda
        Route::post('/', [PrendasController::class, 'store']);
        // Actualizar prenda por Id
        Route::put('/{id}', [PrendasController::class, 'update']);
        // Eliminar prenda por Id
        Route::delete('/{id}', [PrendasController::class, 'destroy']);
    });

    // Notificaciones
    Route::prefix('notificaciones')->group(function () {
        // Listar todas las notificaciones
        Route::get('/', [NotificacionesController::class, 'index']); // filtrar ?receptorId=&remitenteId=&trabajoId=
        // Crear notificación
        Route::post('/', [NotificacionesController::class, 'store']);
        // Actualizar notificación
        Route::put('/{id}', [NotificacionesController::class, 'update']);
        // Eliminar notificación
        Route::delete('/{id}', [NotificacionesController::class, 'destroy']);
    });

    // Inventario
    Route::prefix('inventario')->group(function () {
        // Listar todos los inventario
        Route::get('/', [InventarioController::class, 'index']);
        // Obtener inventario bajo stock
        Route::get('/bajo-stock', [InventarioController::class, 'bajoStock']);
        // Obtener un item por id
        Route::get('/{id}', [InventarioController::class, 'show']);
        // Crear inventario
        Route::post('/', [InventarioController::class, 'store']);
        // Actualizar inventario
        Route::put('/{id}', [InventarioController::class, 'update']);
        // Eliminar inventario
        Route::delete('/{id}', [InventarioController::class, 'destroy']);
    });

    // Facturas
    Route::prefix('facturas')->group(function () {
        // Listar todas las facturas
        Route::get('/', [FacturasController::class, 'index']); // filtro opcional ?trabajoId=&usuarioId=
        // Obtener una factura específica por su ID, incluyendo los trabajos asociados
        Route::get('/{id}', [FacturasController::class, 'show']);
        // Crear una nueva factura (requiere usuarioId y fecha)
        Route::post('/', [FacturasController::class, 'store']);
        // Actualizar una factura existente por su ID (usuarioId, fecha, pagado, total_calculado)
        Route::put('/{id}', [FacturasController::class, 'update']);
        // Eliminar una factura por su ID, también desasocia los trabajos relacionados
        Route::delete('/{id}', [FacturasController::class, 'destroy']);

        //Asociar / desasociar trabajos a facturas
        // Asociar un trabajo a una factura (requiere trabajoId en el body)
        Route::post('/{id}/asociar-trabajo', [FacturasController::class, 'asociarTrabajo']);
        // Desasociar un trabajo de una factura (requiere trabajoId en el body)
        Route::post('/{id}/desasociar-trabajo', [FacturasController::class, 'desasociarTrabajo']);

        //Cálculos para facturas
        // Calcular el total de una factura sumando los precios de todos los trabajos asociados
        Route::get('/{id}/calcular-total', [FacturasController::class, 'calcularTotal']);
    });

    // Calendario / Eventos
    Route::prefix('eventos')->group(function () {
        // Listar todos los eventos
        Route::get('/', [CalendarioController::class, 'index']); // filtrar ?usuarioId=&empleadoId=&trabajoId=
        // Obtener evento por ID   
        Route::get('/{id}', [CalendarioController::class, 'show']);
        // Crear evento
        Route::post('/', [CalendarioController::class, 'store']);
        // Actualizar evento  
        Route::put('/{id}', [CalendarioController::class, 'update']);
        // Eliminar evento 
        Route::delete('/{id}', [CalendarioController::class, 'destroy']);
    });

    // Manejo de preflight CORS
    Route::options('{any}', function () {
        return response()->json([], 200);
    })->where('any', '.*');
});
