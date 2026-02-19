<?php

namespace App\Http\Controllers;

use App\Models\Facturas;
use App\Models\Trabajos;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class FacturasController extends Controller
{
    public function index(Request $request)
    {

        $query = Facturas::with('trabajos');

        if ($request->has('trabajoId')) {
            $query->whereHas('trabajos', function ($q) use ($request) {
                $q->where('trabajoId', $request->trabajoId);
            });
        }

        if ($request->has('usuarioId')) {
            $query->where('usuarioId', $request->usuarioId);
        }

        $facturas = $query->get();

        if (($request->has('usuarioId') || $request->has('trabajoId'))
            && $facturas->isEmpty()
        ) {
            return response()->json([
                'success' => false,
                'message' => 'No se encontraron facturas con los filtros aplicados.'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $facturas
        ]);
    }

    public function show($id)
    {
        $factura = Facturas::with('trabajos')->find($id);

        if (!$factura) {

            return response()->json([
                'success' => false,
                'message' => 'Factura no encontrada.'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $factura
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'usuarioId' => 'required|integer|min:1',
            'fecha' => 'required|date',
        ]);

        try {

            $factura = Facturas::create($data);

            return response()->json([
                'success' => true,
                'data' => $factura
            ], 201);
        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => 'Error al crear la factura.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $factura = Facturas::findOrFail($id);

        if (!$factura) {

            return response()->json([
                'success' => false,
                'message' => 'Factura no encontrado'
            ], 404);
        }

        $data = $request->validate([
            'usuarioId' => 'required|integer|min:1',
            'fecha' => 'required|date',
            'pagado' => 'nullable|boolean',
            'total_calculado' => 'nullable|numeric',
        ]);

        try {
            $factura->update($data);

            return response()->json([
                'success' => true,
                'data' => $factura
            ]);
        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => 'Ocurrió un error al actualizar la Factura',
                'detalle' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {

        $factura = Facturas::findOrFail($id);

        if (!$factura) {

            return response()->json([
                'success' => false,
                'message' => 'Inventario no encontrado'
            ], 404);
        }

        try {
            $factura->trabajos()->detach();
            $factura->delete();

            return response()->json([
                'success' => true,
                'message' => 'Factura eliminada correctamente.'
            ]);
        } catch (QueryException $e) {

            return response()->json([
                'success' => false,
                'message' => 'No se puede eliminar la factura esta asociada.',
                'detalle' => $e->getMessage()
            ], 409);
        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => 'Ocurrió un error al eliminar la Factura',
                'detalle' => $e->getMessage()
            ], 500);
        }
    }

    public function asociarTrabajo(Request $request, $facturaId)
    {
        try {
            $factura = Facturas::findOrFail($facturaId);

            $request->validate([
                'trabajoId' => 'required|integer|exists:trabajos,trabajoId',
            ]);

            $factura->trabajos()->attach($request->trabajoId);

            return response()->json([
                'success' => true,
                'message' => 'Trabajo asociado correctamente.'
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {

            return response()->json([
                'success' => false,
                'message' => 'Factura no encontrada.'
            ], 404);
        } catch (\Illuminate\Validation\ValidationException $e) {

            return response()->json([
                'success' => false,
                'message' => 'Error de validación.',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => 'No se pudo asociar el trabajo.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function desasociarTrabajo(Request $request, $facturaId)
    {
        try {
            $factura = Facturas::findOrFail($facturaId);

            $request->validate([
                'trabajoId' => 'required|integer|exists:trabajos,trabajoId',
            ]);

            $factura->trabajos()->detach($request->trabajoId);

            return response()->json([
                'success' => true,
                'message' => 'Trabajo desasociado correctamente.'
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {

            return response()->json([
                'success' => false,
                'message' => 'Factura no encontrada.'
            ], 404);
        } catch (\Illuminate\Validation\ValidationException $e) {

            return response()->json([
                'success' => false,
                'message' => 'Error de validación.',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => 'No se pudo desasociar el trabajo.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function calcularTotal($id)
    {
        try {

            $factura = Facturas::findOrFail($id);
            $total = $factura->trabajos()->sum('precio');

            $factura->total_calculado = $total;
            $factura->save();

            return response()->json([
                'success' => true,
                'total' => $total
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Factura no encontrada.'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al calcular el total.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
