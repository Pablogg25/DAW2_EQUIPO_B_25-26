<?php

namespace App\Http\Controllers;

use App\Models\Facturas;
use App\Models\Trabajos;
use App\Models\Usuarios;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;

class FacturasController extends Controller
{

    public function index(Request $request)
    {
        try {

            /** @var Usuarios $user */
            $user = $request->user();

            $query = Facturas::with('trabajos');

            if ($user->rol === 'cliente') {
                $query->where('usuarioId', $user->usuarioId);
            }

            if ($user->rol === 'empleado') {
                $query->where('empleadoId', $user->usuarioId);
            }

            return $this->success($query->get());

        } catch (\Throwable $e) {

            return $this->error('Error al listar facturas', 500, $e->getMessage());
        }
    }

    public function show(Request $request, int $id)
    {
        try {

            /** @var Usuarios $user */
            $user = $request->user();

            $factura = Facturas::with('trabajos')->find($id);

            if (!$factura) {
                return $this->error('Factura no encontrada', 404);
            }

            if ($user->rol === 'cliente' &&
                $factura->usuarioId != $user->usuarioId) {
                return $this->error('No autorizado', 403);
            }

            if ($user->rol === 'empleado' &&
                $factura->empleadoId != $user->usuarioId) {
                return $this->error('No autorizado', 403);
            }

            return $this->success($factura);

        } catch (\Throwable $e) {

            return $this->error('Error al obtener factura', 500, $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        try {

            /** @var Usuarios $user */
            $user = $request->user();

            $request->merge([
                'usuarioId'  => $request->usuarioId ?: null,
                'empleadoId' => $request->empleadoId ?: null,
            ]);

            $validated = $request->validate([
                'numero'     => 'required|string|max:100',
                'fecha'      => 'required|date',
                'total'      => 'nullable|numeric',
                'usuarioId'  => 'nullable|integer',
                'empleadoId' => 'nullable|integer',
            ]);

            if ($user->rol === 'cliente') {
                return $this->error('Cliente no puede crear facturas', 403);
            }

            if ($user->rol === 'empleado') {
                $validated['empleadoId'] = $user->usuarioId;
            }

            $factura = Facturas::create($validated);

            return $this->success($factura, 'Factura creada', 201);

        } catch (\Throwable $e) {

            return $this->error('Error al crear factura', 500, $e->getMessage());
        }
    }

    public function update(Request $request, int $id)
    {
        try {

            /** @var Usuarios $user */
            $user = $request->user();

            $factura = Facturas::find($id);

            if (!$factura) {
                return $this->error('Factura no encontrada', 404);
            }

            if ($user->rol === 'cliente') {
                return $this->error('Cliente no puede editar', 403);
            }

            if ($user->rol === 'empleado' &&
                $factura->empleadoId != $user->usuarioId) {
                return $this->error('No autorizado', 403);
            }

            $request->merge([
                'usuarioId'  => $request->usuarioId ?: null,
                'empleadoId' => $request->empleadoId ?: null,
            ]);

            $validated = $request->validate([
                'numero'     => 'required|string|max:100',
                'fecha'      => 'required|date',
                'total'      => 'nullable|numeric',
                'usuarioId'  => 'nullable|integer',
                'empleadoId' => 'nullable|integer',
            ]);

            $factura->update($validated);

            return $this->success($factura, 'Factura actualizada');

        } catch (\Throwable $e) {

            return $this->error('Error al actualizar factura', 500, $e->getMessage());
        }
    }

    public function destroy(Request $request, int $id)
    {
        try {

            /** @var Usuarios $user */
            $user = $request->user();

            if ($user->rol !== 'admin') {
                return $this->error('Solo admin puede eliminar', 403);
            }

            $factura = Facturas::find($id);

            if (!$factura) {
                return $this->error('Factura no encontrada', 404);
            }

            $factura->delete();

            return $this->success(null, 'Factura eliminada');

        } catch (\Throwable $e) {

            return $this->error('Error al eliminar factura', 500, $e->getMessage());
        }
    }

    public function asociarTrabajo(Request $request, int $id)
    {
        try {

            $factura = Facturas::find($id);

            if (!$factura) {
                return $this->error('Factura no encontrada', 404);
            }

            $validated = $request->validate([
                'trabajoId' => 'required|integer'
            ]);

            $trabajo = Trabajos::find($validated['trabajoId']);

            if (!$trabajo) {
                return $this->error('Trabajo no encontrado', 404);
            }

            $factura->trabajos()->syncWithoutDetaching([$trabajo->trabajoId]);

            return $this->success(null, 'Trabajo asociado correctamente');

        } catch (\Throwable $e) {

            return $this->error('Error al asociar trabajo', 500, $e->getMessage());
        }
    }

    public function desasociarTrabajo(Request $request, int $id)
    {
        try {

            $factura = Facturas::find($id);

            if (!$factura) {
                return $this->error('Factura no encontrada', 404);
            }

            $validated = $request->validate([
                'trabajoId' => 'required|integer'
            ]);

            $factura->trabajos()->detach($validated['trabajoId']);

            return $this->success(null, 'Trabajo desasociado');

        } catch (\Throwable $e) {

            return $this->error('Error al desasociar trabajo', 500, $e->getMessage());
        }
    }

    public function calcularTotal(int $id)
    {
        try {

            $factura = Facturas::with('trabajos')->find($id);

            if (!$factura) {
                return $this->error('Factura no encontrada', 404);
            }

            $total = $factura->trabajos->sum('precio');

            $factura->total = $total;
            $factura->save();

            return $this->success([
                'total' => $total
            ], 'Total recalculado');

        } catch (\Throwable $e) {

            return $this->error('Error al calcular total', 500, $e->getMessage());
        }
    }
}