<?php

namespace App\Http\Controllers;

use App\Models\Inventario;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class InventarioController extends Controller
{
    public function index(Request $request)
    {
        $query = Inventario::query();

        // Filtro opcional por nombre
        if ($request->has('nombre')) {
            $query->where('nombre', 'like', '%' . $request->nombre . '%');
        }

        $inventarios = $query->get();

        if ($request->has('nombre') && $inventarios->isEmpty()) {

            return response()->json([
                'success' => false,
                'message' => 'No se encontro el nombre del producto en el inventario.'
            ], 404);

        }

        return response()->json([
            'success' => true,
            'data' => $inventarios
        ]);

    }

    public function show($id)
    {
        $inventario = Inventario::find($id);

        if (!$inventario) {

            return response()->json([
                'success' => false,
                'message' => 'Inventario no encontrado'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $inventario
        ]);
    }

    public function bajoStock()
    {
        $inventarios = Inventario::whereColumn('cantidad', '<=', 'stock_minimo')->get();

        if ($inventarios->isEmpty()) {

            return response()->json([
                'success' => true,
                'message' => 'No hay productos con bajo stock.',
                'data' => []
            ]);
        }

        return response()->json([
            'success' => true,
            'data' => $inventarios
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string|max:500',
            'cantidad' => 'nullable|integer|min:0',
            'stock_minimo' => 'nullable|integer|min:0',
        ]);

        try {

            $inventario = Inventario::create($data);

            return response()->json([
                'success' => true,
                'message' => 'Inventario creado correctamente',
                'data' => $inventario
            ], 201);

        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => 'Ocurrió un error al crear el inventario',
                'detalle' => $e->getMessage()
            ], 500);

        }
    }

    public function update(Request $request, $id)
    {
        $inventario = Inventario::find($id);

        if (!$inventario) {

            return response()->json([
                'success' => false,
                'message' => 'Inventario no encontrado'
            ], 404);
        }

        $data = $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string|max:500',
            'cantidad' => 'nullable|integer|min:0',
            'stock_minimo' => 'nullable|integer|min:0',
        ]);

        try {

            $inventario->update($data);

            return response()->json([
                'success' => true,
                'message' => 'Inventario actualizado correctamente',
                'data' => $inventario
            ]);

        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => 'Ocurrió un error al actualizar el inventario',
                'detalle' => $e->getMessage()
            ], 500);

        }
    }

    public function destroy($id)
    {
        $inventario = Inventario::find($id);

        if (!$inventario) {

            return response()->json([
                'success' => false,
                'message' => 'Inventario no encontrado'
            ], 404);
        }

        try {

            $inventario->delete();

            return response()->json([
                'success' => true,
                'message' => 'Inventario eliminado correctamente'
            ]);
        } catch (QueryException $e) {

            return response()->json([
                'success' => false,
                'message' => 'No se puede eliminar el inventario porque está asociado a un trabajo',
                'detalle' => $e->getMessage()
            ], 409);

        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => 'Ocurrió un error al eliminar el inventario',
                'detalle' => $e->getMessage()
            ], 500);

        }
    }
}
