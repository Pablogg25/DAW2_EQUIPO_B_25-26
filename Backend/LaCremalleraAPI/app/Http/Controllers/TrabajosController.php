<?php

namespace App\Http\Controllers;

use App\Models\Trabajos;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;


class TrabajosController extends Controller
{

    public function index(Request $request)
    {
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

        $trabajos = $query->get();

        // Si se aplicó cualquier filtro y no hay resultados
        if (($request->has('empleadoId') || $request->has('estado') || $request->has('prendaId')) && $trabajos->isEmpty()) {

            return response()->json([
                'success' => false,
                'message' => 'No se encontraron trabajos por ningun filtro.'
            ], 404);

        }

        return response()->json([
            'success' => true,
            'data' => $trabajos
        ]);
    }

    // trabajo restriccion de roles 
    // public function index(Request $request)
    // {
    //     $user = $request->user();

    //     if ($user->role === 'admin') {
    //         return Trabajos::all();
    //     }

    //     if ($user->role === 'empleado') {
    //         return Trabajos::where('empleado_id', $user->id)->get();
    //     }

    //     if ($user->role === 'cliente') {
    //         return Trabajos::where('cliente_id', $user->id)->get();
    //     }
    // }

    public function show($id)
    {
        $trabajo = Trabajos::find($id);

        if (!$trabajo) {
            return response()->json([
                'success' => false,
                'message' => 'Trabajo no encontrado'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $trabajo
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'prendaId' => 'required|integer',
            'empleadoId' => 'nullable|integer',
            'descripcion' => 'nullable|string|max:500',
            'fecha_inicio' => 'required|date',
            'fecha_entrega' => 'required|date|after_or_equal:fecha_inicio',
            'estado' => 'nullable|in:pendiente,en_proceso,listo,entregado',
            'precio' => 'nullable|numeric|min:0',
        ]);

        try {

            $trabajo = Trabajos::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'Trabajo creado correctamente',
                'data' => $trabajo
            ], 201);
        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => 'Ocurrió un error al crear el trabajo',
                'detalle' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $trabajo = Trabajos::find($id);

        if (!$trabajo) {

            return response()->json([
                'success' => false,
                'message' => 'Trabajo no encontrado'
            ], 404);
        }

        $validated = $request->validate([
            'prendaId' => 'required|integer',
            'empleadoId' => 'nullable|integer',
            'descripcion' => 'nullable|string|max:500',
            'fecha_inicio' => 'required|date',
            'fecha_entrega' => 'required|date|after_or_equal:fecha_inicio',
            'estado' => 'nullable|in:pendiente,en_proceso,listo,entregado',
            'precio' => 'nullable|numeric|min:0',
        ]);

        try {

            $trabajo->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Trabajo actualizado correctamente',
                'data' => $trabajo
            ]);
        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => 'Ocurrió un error al actualizar el trabajo',
                'detalle' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        $trabajo = Trabajos::find($id);

        if (!$trabajo) {

            return response()->json([
                'success' => false,
                'message' => 'Trabajo no encontrado'
            ], 404);
        }

        try {

            // Verificar relaciones antes de borrar
            if ($trabajo->consumos()->exists()) {

                return response()->json([
                    'success' => false,
                    'message' => 'No se puede eliminar el trabajo porque tiene consumos asociados'
                ], 409);
            }

            $trabajo->delete();

            return response()->json([
                'success' => true,
                'message' => 'Trabajo eliminado correctamente'
            ]);
        } catch (QueryException $e) {

            return response()->json([
                'success' => false,
                'message' => 'No se puede eliminar el trabajo debido a un conflicto en la base de datos',
                'detalle' => $e->getMessage()
            ], 409);
        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => 'Ocurrió un error al eliminar el trabajo',
                'detalle' => $e->getMessage()
            ], 500);
        }
    }

    public function consumos($id)
    {
        $trabajo = Trabajos::find($id);

        if (!$trabajo) {

            return response()->json([
                'success' => false,
                'message' => 'Trabajo no encontrado'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $trabajo->consumos
        ]);
    }

    public function asociarConsumo(Request $request, $id)
    {
        $trabajo = Trabajos::find($id);

        if (!$trabajo) {

            return response()->json([
                'success' => false,
                'message' => 'Trabajo no encontrado'
            ], 404);
        }

        $validated = $request->validate([
            'itemId' => 'required|integer',
            'cantidad_usada' => 'nullable|integer|min:0',
        ]);

        try {

            $consumo = $trabajo->consumos()->create($validated);

            return response()->json([
                'success' => true,
                'message' => 'Consumo asociado correctamente',
                'data' => $consumo
            ], 201);
        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => 'Ocurrió un error al asociar el consumo',
                'detalle' => $e->getMessage()
            ], 500);
        }
    }
}
