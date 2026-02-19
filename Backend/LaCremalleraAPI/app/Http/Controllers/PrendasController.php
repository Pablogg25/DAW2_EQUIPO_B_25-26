<?php

namespace App\Http\Controllers;

use App\Models\Prendas;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;

class PrendasController extends Controller
{

    public function index(Request $request)
    {
        $query = Prendas::query();

        if ($request->has('usuarioId')) {
            $query->where('usuarioId', $request->usuarioId);
        }

        $prendas = $query->get();

        if ($request->has('usuarioId') && $prendas->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'No se encontraron la prenda o por el filtro de usuarioId.'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $prendas
        ]);
    }

    public function show($id)
    {
        $prenda = Prendas::find($id);

        if (!$prenda) {

            return response()->json([
                'success' => false,
                'message' => 'Prenda no encontrada'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $prenda
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'usuarioId' => 'required|integer',
            'tipo' => 'required|string|max:255',
            'descripcion' => 'nullable|string|max:500',
            'color' => 'nullable|string|max:50',
            'talla' => 'nullable|string|max:10',
        ]);

        try {

            $prenda = Prendas::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'Prenda creada correctamente',
                'data' => $prenda
            ], 201);
        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => 'Ocurrió un error al crear la prenda',
                'detalle' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $prenda = Prendas::find($id);

        if (!$prenda) {

            return response()->json([
                'success' => false,
                'message' => 'Prenda no encontrada'
            ], 404);
        }

        $validated = $request->validate([
            'usuarioId' => 'required|integer',
            'tipo' => 'required|string|max:255',
            'descripcion' => 'nullable|string|max:500',
            'color' => 'nullable|string|max:50',
            'talla' => 'nullable|string|max:10',
        ]);

        try {

            $prenda->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Prenda actualizada correctamente',
                'data' => $prenda
            ]);
        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => 'Ocurrió un error al actualizar la prenda',
                'detalle' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        $prenda = Prendas::find($id);

        if (!$prenda) {

            return response()->json([
                'success' => false,
                'message' => 'Prenda no encontrada'
            ], 404);
        }

        try {
            // opcional: verificar relaciones futuras antes de borrar

            $prenda->delete();

            return response()->json([
                'success' => true,
                'message' => 'Prenda eliminada correctamente'
            ]);
        } catch (QueryException $e) {

            return response()->json([
                'success' => false,
                'message' => 'No se puede eliminar la prenda debido a un conflicto en la base de datos',
                'detalle' => $e->getMessage()
            ], 409);
        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => 'Ocurrió un error al eliminar la prenda',
                'detalle' => $e->getMessage()
            ], 500);
        }
    }
}
