<?php

namespace App\Http\Controllers;

use App\Models\Trabajos;
use App\Models\Usuarios;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;

class TrabajosController extends Controller
{

    public function index(Request $request)
    {
        try {

            /** @var \App\Models\Usuarios $user */
            $user = $request->user();

            $query = Trabajos::with('prenda');

            // Filtros opcionales
            if ($request->filled('empleadoId')) {
                $query->where('empleadoId', $request->empleadoId);
            }

            if ($request->filled('estado')) {
                $query->where('estado', $request->estado);
            }

            if ($request->filled('prendaId')) {
                $query->where('prendaId', $request->prendaId);
            }

            // CLIENTE → solo trabajos de sus prendas
            if ($user->rol === 'cliente') {

                $query->whereHas('prenda', function ($q) use ($user) {
                    $q->where('usuarioId', $user->usuarioId);
                });
            }

            // EMPLEADO → solo trabajos asignados a él
            if ($user->rol === 'empleado') {
                $query->where('empleadoId', $user->usuarioId);
            }

            $trabajos = $query->get();

            return $this->success($trabajos);
        } catch (\Throwable $e) {

            return $this->error(
                'Error al listar trabajos',
                500,
                $e->getMessage()
            );
        }
    }

    public function show(Request $request, int $id)
    {
        try {

            /** @var \App\Models\Usuarios $user */
            $user = $request->user();

            $trabajo = Trabajos::with('prenda')->find($id);

            if (!$trabajo) {
                return $this->error('Trabajo no encontrado', 404);
            }

            // CLIENTE
            if ($user->rol === 'cliente') {

                if ($trabajo->prenda->usuarioId != $user->usuarioId) {
                    return $this->error('No autorizado', 403);
                }
            }

            // EMPLEADO
            if ($user->rol === 'empleado') {

                if ($trabajo->empleadoId != $user->usuarioId) {
                    return $this->error('No autorizado', 403);
                }
            }

            return $this->success($trabajo);
        } catch (\Throwable $e) {

            return $this->error(
                'Error al obtener trabajo',
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

            $validated = $request->validate([
                'prendaId'      => 'required|integer',
                'empleadoId'    => 'nullable|integer',
                'descripcion'   => 'nullable|string|max:500',
                'fecha_inicio'  => 'required|date',
                'fecha_entrega' => 'required|date|after_or_equal:fecha_inicio',
                'estado'        => 'nullable|string',
                'precio'        => 'nullable|numeric',
            ]);

            if ($user->rol === 'cliente') {
                $validated['usuarioId'] = $user->usuarioId;
            }

            if ($user->rol === 'empleado') {
                $validated['empleadoId'] = $user->usuarioId;
            }

            $trabajo = Trabajos::create($validated);

            return $this->success($trabajo, 'Trabajo creado', 201);
        } catch (\Throwable $e) {

            return $this->error(
                'Error al crear trabajo',
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

            $trabajo = Trabajos::find($id);

            if (!$trabajo) {
                return $this->error('Trabajo no encontrado', 404);
            }

            if ($user->rol === 'cliente') {
                return $this->error('Cliente no puede actualizar', 403);
            }

            if (
                $user->rol === 'empleado' &&
                $trabajo->empleadoId != $user->usuarioId
            ) {
                return $this->error('No autorizado', 403);
            }

            $validated = $request->validate([
                'prendaId'      => 'required|integer',
                'empleadoId'    => 'nullable|integer',
                'descripcion'   => 'nullable|string|max:500',
                'fecha_inicio'  => 'required|date',
                'fecha_entrega' => 'required|date|after_or_equal:fecha_inicio',
                'estado'        => 'nullable|string',
                'precio'        => 'nullable|numeric',
            ]);

            $trabajo->update($validated);

            return $this->success($trabajo, 'Trabajo actualizado');
        } catch (\Throwable $e) {

            return $this->error(
                'Error al actualizar trabajo',
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

            $trabajo = Trabajos::find($id);

            if (!$trabajo) {
                return $this->error('Trabajo no encontrado', 404);
            }

            if ($user->rol === 'cliente') {
                return $this->error('No autorizado', 403);
            }

            if (
                $user->rol === 'empleado' &&
                $trabajo->empleadoId != $user->usuarioId
            ) {
                return $this->error('No autorizado', 403);
            }

            if ($trabajo->consumos()->exists()) {
                return $this->error(
                    'No se puede eliminar, tiene consumos asociados',
                    409
                );
            }

            $trabajo->delete();

            return $this->success(null, 'Trabajo eliminado');
        } catch (QueryException $e) {

            return $this->error(
                'Error en base de datos',
                409,
                $e->getMessage()
            );
        } catch (\Throwable $e) {

            return $this->error(
                'Error al eliminar trabajo',
                500,
                $e->getMessage()
            );
        }
    }

    public function consumos(int $id)
    {
        try {

            $trabajo = Trabajos::find($id);

            if (!$trabajo) {
                return $this->error('Trabajo no encontrado', 404);
            }

            return $this->success($trabajo->consumos);
        } catch (\Throwable $e) {

            return $this->error(
                'Error al obtener consumos',
                500,
                $e->getMessage()
            );
        }
    }

    public function asociarConsumo(Request $request, int $id)
    {
        try {

            $trabajo = Trabajos::find($id);

            if (!$trabajo) {
                return $this->error('Trabajo no encontrado', 404);
            }

            $validated = $request->validate([
                'itemId' => 'required|integer',
                'cantidad_usada' => 'nullable|integer|min:1',
            ]);

            $consumo = $trabajo->consumos()->create($validated);

            return $this->success(
                $consumo,
                'Consumo asociado correctamente',
                201
            );
        } catch (\Throwable $e) {

            return $this->error(
                'Error al asociar consumo',
                500,
                $e->getMessage()
            );
        }
    }
}
