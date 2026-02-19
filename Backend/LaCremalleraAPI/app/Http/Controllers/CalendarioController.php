<?php

namespace App\Http\Controllers;

use App\Models\Calendario;
use Illuminate\Http\Request;

class CalendarioController extends Controller
{

    public function index(Request $request)
    {
        $query = Calendario::query();

        if ($request->has('usuarioId')) {
            $query->where('usuarioId', $request->usuarioId);
        }

        if ($request->has('empleadoId')) {
            $query->where('empleadoId', $request->empleadoId);
        }

        if ($request->has('trabajoId')) {
            $query->where('trabajoId', $request->trabajoId);
        }

        $calendarios = $query->get();

        if ($calendarios->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'No se encontraron calendarios con los filtros aplicados.'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $calendarios
        ]);
    }

    public function show($id)
    {
        $calendario = Calendario::find($id);

        if (!$calendario) {

            return response()->json([
                'success' => false,
                'message' => 'Calendario no encontrado.'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $calendario
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'titulo' => 'required|string',
            'descripcion' => 'nullable|string',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date',
            'usuarioId' => 'required|integer',
            'empleadoId' => 'nullable|integer',
            'trabajoId' => 'nullable|integer',
        ]);

        try {

            $calendario = Calendario::create($validated);

            return response()->json([
                'success' => true,
                'data' => $calendario
            ], 201);
        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => 'Ocurrió un error al crear la calendario',
                'detalle' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $calendario = Calendario::find($id);

        if (!$calendario) {
            return response()->json([
                'success' => false,
                'message' => 'Calendario no encontrado.'
            ], 404);
        }

        $validated = $request->validate([
            'titulo' => 'required|string',
            'descripcion' => 'nullable|string',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date',
            'usuarioId' => 'required|integer',
            'empleadoId' => 'nullable|integer',
            'trabajoId' => 'nullable|integer',
        ]);

        try {

            $calendario->update($validated);

            return response()->json([
                'success' => true,
                'data' => $calendario
            ]);
        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => 'Ocurrió un error al actualizar el calendario',
                'detalle' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        $calendario = Calendario::find($id);

        if (!$calendario) {
            return response()->json([
                'success' => false,
                'message' => 'Calendario no encontrado.'
            ], 404);
        }

        try {

            $calendario->delete();
            return response()->json([
                'success' => true,
                'message' => 'Calendario eliminado correctamente.'
            ]);

        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => 'No se pudo eliminar el calendario.',
                'error' => $e->getMessage()
            ], 500);
            
        }
    }
}
