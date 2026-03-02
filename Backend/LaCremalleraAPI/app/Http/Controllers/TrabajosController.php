<?php

namespace App\Http\Controllers;

use App\Models\Trabajos;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class TrabajosController extends Controller
{

    public function index(Request $request)
    {
        try {

            $user = $request->user();

            $query = Trabajos::query();

            if ($request->has('empleadoId')) {
                $query->where('empleadoId', $request->empleadoId);
            }

            if ($request->has('estado')) {
                $query->where('estado', $request->estado);
            }

            if ($request->has('prendaId')) {
                $query->where('prendaId', $request->prendaId);
            }

            // ownership

            if ($user->rol === 'cliente') {
                $query->where('usuarioId', $user->usuarioId);
            }

            if ($user->rol === 'empleado') {
                $query->where('empleadoId', $user->usuarioId);
            }

            $trabajos = $query->get();

            if (
                ($request->has('empleadoId') ||
                 $request->has('estado') ||
                 $request->has('prendaId'))
                && $trabajos->isEmpty()
            ) {
                return $this->error('No se encontraron trabajos', 404);
            }

            return $this->success($trabajos);

        } catch (\Throwable $e) {

            return $this->error('Error al listar trabajos', 500, $e->getMessage());
        }
    }


    public function show(Request $request, $id)
    {
        try {

            $user = $request->user();

            $trabajo = Trabajos::find($id);

            if (!$trabajo) {
                return $this->error('Trabajo no encontrado', 404);
            }

            if (
                $user->rol === 'cliente' &&
                $trabajo->usuarioId != $user->usuarioId
            ) {
                return $this->error('No autorizado', 403);
            }

            if (
                $user->rol === 'empleado' &&
                $trabajo->empleadoId != $user->usuarioId
            ) {
                return $this->error('No autorizado', 403);
            }

            return $this->success($trabajo);

        } catch (\Throwable $e) {

            return $this->error('Error al obtener trabajo', 500, $e->getMessage());
        }
    }


    public function store(Request $request)
    {
        try {

            $user = $request->user();

            $validated = $request->validate([
                'prendaId' => 'required|integer',
                'empleadoId' => 'nullable|integer',
                'descripcion' => 'nullable|string|max:500',
                'fecha_inicio' => 'required|date',
                'fecha_entrega' => 'required|date',
                'estado' => 'nullable|string',
                'precio' => 'nullable|numeric',
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

            return $this->error('Error al crear trabajo', 500, $e->getMessage());
        }
    }


    public function update(Request $request, $id)
    {
        try {

            $user = $request->user();

            $trabajo = Trabajos::find($id);

            if (!$trabajo) {
                return $this->error('Trabajo no encontrado', 404);
            }

            if (
                $user->rol === 'empleado' &&
                $trabajo->empleadoId != $user->usuarioId
            ) {
                return $this->error('No autorizado', 403);
            }

            if ($user->rol === 'cliente') {
                return $this->error('Cliente no puede actualizar', 403);
            }

            $validated = $request->validate([
                'prendaId' => 'required|integer',
                'empleadoId' => 'nullable|integer',
                'descripcion' => 'nullable|string|max:500',
                'fecha_inicio' => 'required|date',
                'fecha_entrega' => 'required|date',
                'estado' => 'nullable|string',
                'precio' => 'nullable|numeric',
            ]);

            $trabajo->update($validated);

            return $this->success($trabajo, 'Trabajo actualizado');

        } catch (\Throwable $e) {

            return $this->error('Error al actualizar', 500, $e->getMessage());
        }
    }


    public function destroy(Request $request, $id)
    {
        try {

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
                    'No se puede eliminar, tiene consumos',
                    409
                );
            }

            $trabajo->delete();

            return $this->success(null, 'Trabajo eliminado');

        } catch (QueryException $e) {

            return $this->error('Error BD', 409, $e->getMessage());

        } catch (\Throwable $e) {

            return $this->error('Error eliminar', 500, $e->getMessage());
        }
    }


    public function consumos(Request $request, $id)
    {
        try {

            $trabajo = Trabajos::find($id);

            if (!$trabajo) {
                return $this->error('Trabajo no encontrado', 404);
            }

            return $this->success($trabajo->consumos);

        } catch (\Throwable $e) {

            return $this->error('Error consumos', 500, $e->getMessage());
        }
    }


    public function asociarConsumo(Request $request, $id)
    {
        try {

            $trabajo = Trabajos::find($id);

            if (!$trabajo) {
                return $this->error('Trabajo no encontrado', 404);
            }

            $validated = $request->validate([
                'itemId' => 'required|integer',
                'cantidad_usada' => 'nullable|integer',
            ]);

            $consumo = $trabajo
                ->consumos()
                ->create($validated);

            return $this->success(
                $consumo,
                'Consumo asociado',
                201
            );

        } catch (\Throwable $e) {

            return $this->error(
                'Error consumo',
                500,
                $e->getMessage()
            );
        }
    }
}