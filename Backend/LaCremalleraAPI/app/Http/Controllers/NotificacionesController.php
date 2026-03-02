<?php

namespace App\Http\Controllers;

use App\Models\Notificaciones;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;

class NotificacionesController extends Controller
{

    public function index(Request $request)
    {
        try {

            $user = $request->user();

            $query = Notificaciones::query();

            if ($request->has('receptorId')) {
                $query->where('receptorId', $request->receptorId);
            }

            if ($request->has('remitenteId')) {
                $query->where('remitenteId', $request->remitenteId);
            }

            if ($request->has('trabajoId')) {
                $query->where('trabajoId', $request->trabajoId);
            }

            // ownership

            if ($user->rol === 'cliente') {
                $query->where('receptorId', $user->usuarioId);
            }

            if ($user->rol === 'empleado') {
                $query->where(function ($q) use ($user) {
                    $q->where('receptorId', $user->usuarioId)
                        ->orWhere('remitenteId', $user->usuarioId);
                });
            }

            $notificaciones = $query->get();

            if (
                ($request->has('receptorId') ||
                    $request->has('remitenteId') ||
                    $request->has('trabajoId'))
                && $notificaciones->isEmpty()
            ) {
                return $this->error(
                    'No se encontraron notificaciones',
                    404
                );
            }

            return $this->success($notificaciones);
        } catch (\Throwable $e) {

            return $this->error(
                'Error al listar notificaciones',
                500,
                $e->getMessage()
            );
        }
    }


    public function show(Request $request, $id)
    {
        try {

            $user = $request->user();

            $notificacion = Notificaciones::find($id);

            if (!$notificacion) {
                return $this->error(
                    'Notificación no encontrada',
                    404
                );
            }

            if (
                $user->rol === 'cliente' &&
                $notificacion->receptorId != $user->usuarioId
            ) {
                return $this->error(
                    'No autorizado',
                    403
                );
            }

            return $this->success($notificacion);
        } catch (\Throwable $e) {

            return $this->error(
                'Error al obtener notificación',
                500,
                $e->getMessage()
            );
        }
    }


    public function store(Request $request)
    {
        try {

            $user = $request->user();

            if ($user->rol === 'cliente') {
                return $this->error(
                    'Cliente no puede crear',
                    403
                );
            }

            $data = $request->validate([
                'receptorId' => 'required|integer|min:1',
                'remitenteId' => 'required|integer|min:1',
                'trabajoId' => 'nullable|integer|min:1',
                'tipo' => 'nullable|string|in:' . implode(',', Notificaciones::tiposValidos()),
                'asunto' => 'nullable|string|max:255',
                'mensaje' => 'required|string',
            ]);

            $data['tipo'] = $data['tipo'] ?? 'notificacion';
            $data['fecha_envio'] = now();

            $notificacion = Notificaciones::create($data);

            return $this->success(
                $notificacion,
                'Notificación creada',
                201
            );
        } catch (\Throwable $e) {

            return $this->error(
                'Error al crear notificación',
                500,
                $e->getMessage()
            );
        }
    }


    public function update(Request $request, $id)
    {
        try {

            $user = $request->user();

            if ($user->rol === 'cliente') {
                return $this->error(
                    'Cliente no puede actualizar',
                    403
                );
            }

            $notificacion = Notificaciones::find($id);

            if (!$notificacion) {
                return $this->error(
                    'Notificación no encontrada',
                    404
                );
            }

            $data = $request->validate([
                'receptorId' => 'required|integer|min:1',
                'remitenteId' => 'required|integer|min:1',
                'trabajoId' => 'nullable|integer|min:1',
                'tipo' => 'nullable|string|in:' . implode(',', Notificaciones::tiposValidos()),
                'asunto' => 'nullable|string|max:255',
                'mensaje' => 'required|string',
            ]);

            $data['tipo'] = $data['tipo'] ?? 'notificacion';

            $notificacion->update($data);

            return $this->success(
                $notificacion,
                'Notificación actualizada'
            );
        } catch (\Throwable $e) {

            return $this->error(
                'Error al actualizar',
                500,
                $e->getMessage()
            );
        }
    }


    public function destroy(Request $request, $id)
    {
        try {

            $user = $request->user();

            if ($user->rol !== 'admin') {
                return $this->error(
                    'Solo admin',
                    403
                );
            }

            $notificacion = Notificaciones::find($id);

            if (!$notificacion) {
                return $this->error(
                    'No encontrada',
                    404
                );
            }

            $notificacion->delete();

            return $this->success(
                null,
                'Notificación eliminada'
            );
        } catch (QueryException $e) {

            return $this->error(
                'Conflicto BD',
                409,
                $e->getMessage()
            );
        } catch (\Throwable $e) {

            return $this->error(
                'Error eliminar',
                500,
                $e->getMessage()
            );
        }
    }
}
