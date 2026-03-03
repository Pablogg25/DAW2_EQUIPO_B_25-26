<?php

namespace App\Http\Controllers;

use App\Models\Calendario;
use App\Models\Usuarios;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;

class CalendarioController extends Controller
{

    public function index(Request $request)
    {
        try {

            $query = Calendario::query();

            if ($request->filled('usuarioId')) {
                $query->where('usuarioId', $request->usuarioId);
            }

            if ($request->filled('empleadoId')) {
                $query->where('empleadoId', $request->empleadoId);
            }

            if ($request->filled('trabajoId')) {
                $query->where('trabajoId', $request->trabajoId);
            }

            $calendarios = $query->get();

            return $this->success($calendarios);

        } catch (\Throwable $e) {

            return $this->error(
                'Error al listar eventos',
                500,
                $e->getMessage()
            );
        }
    }

    public function show(int $id)
    {
        try {

            $calendario = Calendario::find($id);

            if (!$calendario) {
                return $this->error('Evento no encontrado', 404);
            }

            return $this->success($calendario);

        } catch (\Throwable $e) {

            return $this->error(
                'Error al obtener evento',
                500,
                $e->getMessage()
            );
        }
    }

    public function store(Request $request)
    {
        try {

            /** @var Usuarios $user */
            $user = $request->user();

            // Convertir strings vacíos a null
            $request->merge([
                'empleadoId' => $request->empleadoId ?: null,
                'trabajoId'  => $request->trabajoId ?: null,
                'usuarioId'  => $request->usuarioId ?: null,
            ]);

            $validated = $request->validate([
                'titulo'        => 'required|string|max:255',
                'descripcion'   => 'nullable|string',
                'fecha_inicio'  => 'required|date',
                'fecha_fin'     => 'required|date|after_or_equal:fecha_inicio',
                'usuarioId'     => 'nullable|integer',
                'empleadoId'    => 'nullable|integer',
                'trabajoId'     => 'nullable|integer',
            ]);

            // Lógica por rol

            if ($user->rol === 'cliente') {
                $validated['usuarioId'] = $user->usuarioId;
            }

            if ($user->rol === 'empleado') {
                $validated['empleadoId'] = $user->usuarioId;
            }

            // Admin puede crear libremente sin obligación

            $calendario = Calendario::create($validated);

            return $this->success(
                $calendario,
                'Evento creado correctamente',
                201
            );

        } catch (\Throwable $e) {

            return $this->error(
                'Error al crear evento',
                500,
                $e->getMessage()
            );
        }
    }

    public function update(Request $request, int $id)
    {
        try {

            /** @var Usuarios $user */
            $user = $request->user();

            $calendario = Calendario::find($id);

            if (!$calendario) {
                return $this->error('Evento no encontrado', 404);
            }

            // Convertir strings vacíos a null
            $request->merge([
                'empleadoId' => $request->empleadoId ?: null,
                'trabajoId'  => $request->trabajoId ?: null,
                'usuarioId'  => $request->usuarioId ?: null,
            ]);

            $validated = $request->validate([
                'titulo'        => 'required|string|max:255',
                'descripcion'   => 'nullable|string',
                'fecha_inicio'  => 'required|date',
                'fecha_fin'     => 'required|date|after_or_equal:fecha_inicio',
                'usuarioId'     => 'nullable|integer',
                'empleadoId'    => 'nullable|integer',
                'trabajoId'     => 'nullable|integer',
            ]);

            // 🔐 Restricciones básicas
            if ($user->rol === 'cliente' &&
                $calendario->usuarioId !== $user->usuarioId) {
                return $this->error('No autorizado', 403);
            }

            if ($user->rol === 'empleado' &&
                $calendario->empleadoId !== $user->usuarioId) {
                return $this->error('No autorizado', 403);
            }

            $calendario->update($validated);

            return $this->success(
                $calendario,
                'Evento actualizado correctamente'
            );

        } catch (\Throwable $e) {

            return $this->error(
                'Error al actualizar evento',
                500,
                $e->getMessage()
            );
        }
    }

    public function destroy(Request $request, int $id)
    {
        try {

            /** @var Usuarios $user */
            $user = $request->user();

            $calendario = Calendario::find($id);

            if (!$calendario) {
                return $this->error('Evento no encontrado', 404);
            }

            if ($user->rol !== 'admin') {
                return $this->error('Solo admin puede eliminar eventos', 403);
            }

            $calendario->delete();

            return $this->success(
                null,
                'Evento eliminado correctamente'
            );

        } catch (\Throwable $e) {

            return $this->error(
                'Error al eliminar evento',
                500,
                $e->getMessage()
            );
        }
    }
}