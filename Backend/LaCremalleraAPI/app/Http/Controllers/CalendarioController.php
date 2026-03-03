<?php

namespace App\Http\Controllers;

use App\Models\Calendario;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;

class CalendarioController extends Controller
{
    
    public function index(Request $request)
    {
        try {

            $user = $request->user();
            $query = Calendario::query();

            // FILTRO POR ROL
            if ($user->rol === 'cliente') {
                $query->where('usuarioId', $user->usuarioId);
            }

            if ($user->rol === 'empleado') {
                $query->where('empleadoId', $user->usuarioId);
            }

            $eventos = $query->get();

            return $this->success($eventos);

        } catch (\Throwable $e) {
            return $this->error('Error al listar eventos', 500, $e->getMessage());
        }
    }

    public function show(Request $request, $id)
    {
        try {

            $user = $request->user();
            $evento = Calendario::find($id);

            if (!$evento) {
                return $this->error('Evento no encontrado', 404);
            }

            // CONTROL DE ACCESO
            if ($user->rol !== 'admin') {

                if ($user->rol === 'cliente' &&
                    $evento->usuarioId != $user->usuarioId) {
                    return $this->error('No autorizado', 403);
                }

                if ($user->rol === 'empleado' &&
                    $evento->empleadoId != $user->usuarioId) {
                    return $this->error('No autorizado', 403);
                }
            }

            return $this->success($evento);

        } catch (\Throwable $e) {
            return $this->error('Error al obtener evento', 500, $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        try {

            $user = $request->user();

            // Convertir vacíos a null
            $request->merge([
                'empleadoId' => $request->empleadoId ?: null,
                'trabajoId'  => $request->trabajoId ?: null,
            ]);

            $validated = $request->validate([
                'titulo' => 'required|string|max:100',
                'descripcion' => 'nullable|string',
                'fecha_inicio' => 'required|date',
                'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
                'empleadoId' => 'nullable|integer|exists:usuarios,usuarioId',
                'trabajoId' => 'nullable|integer|exists:trabajos,trabajoId',
            ]);

            // ASIGNACIÓN AUTOMÁTICA SEGÚN ROL

            if ($user->rol === 'cliente') {
                $validated['usuarioId'] = $user->usuarioId;
                $validated['empleadoId'] = null;
            }

            if ($user->rol === 'empleado') {
                $validated['usuarioId'] = $user->usuarioId;
                $validated['empleadoId'] = $user->usuarioId;
            }

            if ($user->rol === 'admin') {
                // Admin debe enviar usuarioId manualmente desde frontend
                $validated['usuarioId'] = $request->usuarioId ?? $user->usuarioId;
            }

            $evento = Calendario::create($validated);

            return $this->success($evento, 'Evento creado', 201);

        } catch (\Throwable $e) {
            return $this->error('Error al crear evento', 500, $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        try {

            $user = $request->user();
            $evento = Calendario::find($id);

            if (!$evento) {
                return $this->error('Evento no encontrado', 404);
            }

            // CONTROL DE ACCESO
            if ($user->rol !== 'admin') {

                if ($user->rol === 'cliente') {
                    return $this->error('Cliente no puede editar', 403);
                }

                if ($user->rol === 'empleado' &&
                    $evento->empleadoId != $user->usuarioId) {
                    return $this->error('No autorizado', 403);
                }
            }

            $request->merge([
                'empleadoId' => $request->empleadoId ?: null,
                'trabajoId'  => $request->trabajoId ?: null,
            ]);

            $validated = $request->validate([
                'titulo' => 'required|string|max:100',
                'descripcion' => 'nullable|string',
                'fecha_inicio' => 'required|date',
                'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
                'empleadoId' => 'nullable|integer|exists:usuarios,usuarioId',
                'trabajoId' => 'nullable|integer|exists:trabajos,trabajoId',
            ]);

            $evento->update($validated);

            return $this->success($evento, 'Evento actualizado');

        } catch (\Throwable $e) {
            return $this->error('Error al actualizar evento', 500, $e->getMessage());
        }
    }

    public function destroy(Request $request, $id)
    {
        try {

            $user = $request->user();

            if ($user->rol !== 'admin') {
                return $this->error('Solo admin puede eliminar', 403);
            }

            $evento = Calendario::find($id);

            if (!$evento) {
                return $this->error('Evento no encontrado', 404);
            }

            $evento->delete();

            return $this->success(null, 'Evento eliminado');

        } catch (QueryException $e) {
            return $this->error('Error BD', 409, $e->getMessage());
        } catch (\Throwable $e) {
            return $this->error('Error al eliminar evento', 500, $e->getMessage());
        }
    }
}