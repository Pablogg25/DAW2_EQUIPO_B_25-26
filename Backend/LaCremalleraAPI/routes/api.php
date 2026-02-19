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
    // Obtener todo los usuarios
    Route::get('/usuarios', [UsuariosController::class, 'index']);
    // Obtener usuario por Id
    Route::get('/usuarios/{id}', [UsuariosController::class, 'show']);
    // Obtner usuario por username
    Route::get('/usuarios/username/{username}', [UsuariosController::class, 'showByUsername']);
    // Crear usuario
    Route::post('/usuarios', [UsuariosController::class, 'store']);
    // Actualizar usuario
    Route::put('/usuarios/{id}', [UsuariosController::class, 'update']);
    // Actualizar contraseña mediante Id del usuario
    Route::put('/usuarios/{id}/password', [UsuariosController::class, 'updatePassword']);
    // Eliminar usuario por Id
    Route::delete('/usuarios/{id}', [UsuariosController::class, 'destroy']);
    // Check password
    Route::post('/login', [UsuariosController::class, 'checkPassword']);

    // Trabajos
    // Obtener todo los trabajos
    Route::get('/trabajos', [TrabajosController::class, 'index']);
    // Obtener trabajor por Id
    Route::get('/trabajos/{id}', [TrabajosController::class, 'show']);
    // Crear nuevo trabajo
    Route::post('/trabajos', [TrabajosController::class, 'store']);
    // Actualizar trabajo por Id
    Route::put('/trabajos/{id}', [TrabajosController::class, 'update']);
    // Eliminar trabajo por Id
    Route::delete('/trabajos/{id}', [TrabajosController::class, 'destroy']);

    // Consumos de un trabajo
    // Obtener consumos por Id del trabajo
    Route::get('/trabajos/{id}/consumos', [TrabajosController::class, 'consumos']);
    // Asociar consumo por Id del trabajo
    Route::post('/trabajos/{id}/consumos', [TrabajosController::class, 'asociarConsumo']);

    // Prendas
    // Obtner todas las prendas
    Route::get('/prendas', [PrendasController::class, 'index']);
    // Obtener prendas por Id
    Route::get('/prendas/{id}', [PrendasController::class, 'show']);
    // Obtener prenda del usuario por Id del usuario
    Route::get('/prendas/usuario/{usuarioId}', [PrendasController::class, 'showByUsuario']);
    // Crear nueva prenda
    Route::post('/prendas', [PrendasController::class, 'store']);
    // Actualizar prenda por Id
    Route::put('/prendas/{id}', [PrendasController::class, 'update']);
    // Eliminar prenda por Id
    Route::delete('/prendas/{id}', [PrendasController::class, 'destroy']);

    //Notificaciones
    // Obtener todas las notificaciones
    Route::get('/notificaciones', [NotificacionesController::class, 'index']);
    // Obtener por receptorId
    Route::get('/notificaciones/receptor/{receptorId}', [NotificacionesController::class, 'byReceptor']);
    // Obtener por remitenteId
    Route::get('/notificaciones/remitente/{remitenteId}', [NotificacionesController::class, 'byRemitente']);
    // Obtener por trabajoId
    Route::get('/notificaciones/trabajo/{trabajoId}', [NotificacionesController::class, 'byTrabajo']);
    // Crear notificación
    Route::post('/notificaciones', [NotificacionesController::class, 'store']);
    // Actualizar notificación
    Route::put('/notificaciones/{id}', [NotificacionesController::class, 'update']);
    // Eliminar notificación
    Route::delete('/notificaciones/{id}', [NotificacionesController::class, 'destroy']);

    //Inventario
    // Obtener todos los inventario
    Route::get('/inventario', [InventarioController::class, 'index']);
    // Obtener un item por id
    Route::get('/inventario/{id}', [InventarioController::class, 'show']);
    // Obtener inventario bajo stock
    Route::get('/inventario/bajo-stock', [InventarioController::class, 'bajoStock']);
    // Crear inventario
    Route::post('/inventario', [InventarioController::class, 'store']);
    // Actualizar inventario
    Route::put('/inventario/{id}', [InventarioController::class, 'update']);
    // Eliminar inventario
    Route::delete('/inventario/{id}', [InventarioController::class, 'destroy']);

    //Facturas
    // Obtener todas las facturas
    Route::get('/facturas', [FacturasController::class, 'index']);
    // Obtener una factura específica por su ID, incluyendo los trabajos asociados
    Route::get('/facturas/{id}', [FacturasController::class, 'show']);
    // // Obtener todas las facturas de un usuario específico por su usuarioId
    // Route::get('/facturas/usuario/{usuarioId}', [FacturasController::class, 'byUsuario']);
    // // Obtener todas las facturas asociadas a un trabajo específico por su trabajoId
    // Route::get('/facturas/trabajo/{trabajoId}', [FacturasController::class, 'byTrabajo']);
    // Crear una nueva factura (requiere usuarioId y fecha)
    Route::post('/facturas', [FacturasController::class, 'store']);
    // Actualizar una factura existente por su ID (usuarioId, fecha, pagado, total_calculado)
    Route::put('/facturas/{id}', [FacturasController::class, 'update']);
    // Eliminar una factura por su ID, también desasocia los trabajos relacionados
    Route::delete('/facturas/{id}', [FacturasController::class, 'destroy']);

    //Asociar / desasociar trabajos a facturas
    // Asociar un trabajo a una factura (requiere trabajoId en el body)
    Route::post('/facturas/{id}/asociar-trabajo', [FacturasController::class, 'asociarTrabajo']);
    // Desasociar un trabajo de una factura (requiere trabajoId en el body)
    Route::post('/facturas/{id}/desasociar-trabajo', [FacturasController::class, 'desasociarTrabajo']);

    //Cálculos
    // Calcular el total de una factura sumando los precios de todos los trabajos asociados
    Route::get('/facturas/{id}/calcular-total', [FacturasController::class, 'calcularTotal']);

    //Calendario
    // Listar todos los eventos
    Route::get('/eventos', [CalendarioController::class, 'index']);
    // Ver evento por ID                
    Route::get('/eventos/{id}', [CalendarioController::class, 'show']);
    // Eventos por usuario  
    Route::get('/eventos/usuario/{usuarioId}', [CalendarioController::class, 'byUsuario']);
    // Eventos por empleado
    Route::get('/eventos/empleado/{empleadoId}', [CalendarioController::class, 'byEmpleado']);
    // Eventos por trabajo
    Route::get('/eventos/trabajo/{trabajoId}', [CalendarioController::class, 'byTrabajo']);
    // Crear evento
    Route::post('/eventos', [CalendarioController::class, 'store']);
    // Actualizar evento            
    Route::put('/eventos/{id}', [CalendarioController::class, 'update']);
    // Eliminar evento       
    Route::delete('/eventos/{id}', [CalendarioController::class, 'destroy']);

    // Manejo de preflight CORS para permitir solicitudes desde el frontend React
    Route::options('{any}', function () {
        return response()->json([], 200);
    })->where('any', '.*');

});


