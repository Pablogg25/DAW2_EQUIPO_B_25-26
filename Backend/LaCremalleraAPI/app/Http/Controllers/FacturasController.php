<?php

namespace App\Http\Controllers;

use App\Models\Facturas;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class FacturasController extends Controller
{

    public function index(Request $request)
    {
        try {

            $user = $request->user();

            $query = Facturas::with('trabajos');

            if ($request->has('trabajoId')) {
                $query->whereHas('trabajos', function ($q) use ($request) {
                    $q->where('trabajoId', $request->trabajoId);
                });
            }

            if ($request->has('usuarioId')) {
                $query->where('usuarioId', $request->usuarioId);
            }

            // cliente solo ve las suyas
            if ($user->rol === 'cliente') {
                $query->where('usuarioId', $user->usuarioId);
            }

            $facturas = $query->get();

            if ($facturas->isEmpty()) {
                return $this->error(
                    'No se encontraron facturas',
                    404
                );
            }

            return $this->success($facturas);
        } catch (\Throwable $e) {

            return $this->error(
                'Error listar facturas',
                500,
                $e->getMessage()
            );
        }
    }


    public function show(Request $request, $id)
    {
        try {

            $user = $request->user();

            $factura = Facturas::with('trabajos')->find($id);

            if (!$factura) {
                return $this->error(
                    'Factura no encontrada',
                    404
                );
            }

            if (
                $user->rol === 'cliente' &&
                $factura->usuarioId != $user->usuarioId
            ) {
                return $this->error(
                    'No autorizado',
                    403
                );
            }

            return $this->success($factura);
        } catch (\Throwable $e) {

            return $this->error(
                'Error obtener factura',
                500,
                $e->getMessage()
            );
        }
    }


    public function store(Request $request)
    {
        try {

            $user = $request->user();

            if (!in_array($user->rol, ['admin', 'empleado'])) {
                return $this->error(
                    'No autorizado',
                    403
                );
            }

            $data = $request->validate([
                'usuarioId' => 'required|integer|min:1',
                'fecha' => 'required|date',
            ]);

            $factura = Facturas::create($data);

            return $this->success(
                $factura,
                'Factura creada',
                201
            );
        } catch (\Throwable $e) {

            return $this->error(
                'Error crear factura',
                500,
                $e->getMessage()
            );
        }
    }


    public function update(Request $request, $id)
    {
        try {

            $user = $request->user();

            if (!in_array($user->rol, ['admin', 'empleado'])) {
                return $this->error(
                    'No autorizado',
                    403
                );
            }

            $factura = Facturas::find($id);

            if (!$factura) {
                return $this->error(
                    'Factura no encontrada',
                    404
                );
            }

            $data = $request->validate([
                'usuarioId' => 'required|integer',
                'fecha' => 'required|date',
                'pagado' => 'nullable|boolean',
                'total_calculado' => 'nullable|numeric',
            ]);

            $factura->update($data);

            return $this->success(
                $factura,
                'Factura actualizada'
            );
        } catch (\Throwable $e) {

            return $this->error(
                'Error actualizar factura',
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

            $factura = Facturas::find($id);

            if (!$factura) {
                return $this->error(
                    'Factura no encontrada',
                    404
                );
            }

            $factura->trabajos()->detach();
            $factura->delete();

            return $this->success(
                null,
                'Factura eliminada'
            );
        } catch (QueryException $e) {

            return $this->error(
                'Conflicto BD',
                409,
                $e->getMessage()
            );
        } catch (\Throwable $e) {

            return $this->error(
                'Error eliminar factura',
                500,
                $e->getMessage()
            );
        }
    }


    public function asociarTrabajo(Request $request, $facturaId)
    {
        try {

            $user = $request->user();

            if ($user->rol !== 'admin') {
                return $this->error(
                    'Solo admin',
                    403
                );
            }

            $factura = Facturas::findOrFail($facturaId);

            $request->validate([
                'trabajoId' => 'required|integer'
            ]);

            $factura->trabajos()->attach(
                $request->trabajoId
            );

            return $this->success(
                null,
                'Trabajo asociado'
            );
        } catch (\Throwable $e) {

            return $this->error(
                'Error asociar',
                500,
                $e->getMessage()
            );
        }
    }


    public function desasociarTrabajo(Request $request, $facturaId)
    {
        try {

            $user = $request->user();

            if ($user->rol !== 'admin') {
                return $this->error(
                    'Solo admin',
                    403
                );
            }

            $factura = Facturas::findOrFail($facturaId);

            $request->validate([
                'trabajoId' => 'required|integer'
            ]);

            $factura->trabajos()->detach(
                $request->trabajoId
            );

            return $this->success(
                null,
                'Trabajo desasociado'
            );
        } catch (\Throwable $e) {

            return $this->error(
                'Error desasociar',
                500,
                $e->getMessage()
            );
        }
    }


    public function calcularTotal(Request $request, $id)
    {
        try {

            $user = $request->user();

            if (!in_array($user->rol, ['admin', 'empleado'])) {
                return $this->error(
                    'No autorizado',
                    403
                );
            }

            $factura = Facturas::findOrFail($id);

            $total = $factura
                ->trabajos()
                ->sum('precio');

            $factura->total_calculado = $total;
            $factura->save();

            return $this->success([
                'total' => $total
            ]);
        } catch (\Throwable $e) {

            return $this->error(
                'Error calcular total',
                500,
                $e->getMessage()
            );
        }
    }
}
