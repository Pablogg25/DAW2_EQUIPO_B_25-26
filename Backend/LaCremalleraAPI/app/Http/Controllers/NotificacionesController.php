<?php

namespace App\Http\Controllers;

use App\Models\Notificaciones;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;

class NotificacionesController extends Controller
{
    public function index(Request $request)
    {

        $query = Notificaciones::query();

        if ($request->has('receptorId')) {
            $query->where('receptorId', $request->receptorId);
        }

        if ($request->has('remitenteId')) {
            $query->where('remitenteId', $request->remitenteId);
        }

        if ($request->has('trabajoId')) {
            $query->where('trabajoId', $request->trabajoId);
        }

        $notificaciones = $query->get();

        if (
            ($request->has('receptorId') || $request->has('remitenteId') || $request->has('trabajoId'))
            && $notificaciones->isEmpty()
        ) {
            return response()->json([
                'success' => false,
                'message' => 'No se encontraron notificaciones con los filtros aplicados.'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $notificaciones
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'receptorId' => 'required|integer|min:1',
            'remitenteId' => 'required|integer|min:1',
            'trabajoId' => 'nullable|integer|min:1',
            'tipo' => 'nullable|string|in:' . implode(',', Notificaciones::tiposValidos()),
            'asunto' => 'nullable|string|max:255',
            'mensaje' => 'required|string',
        ]);

        $data['tipo'] = $data['tipo'] ?? 'notificacion';
        $data['fecha_envio'] = now();

        try {

            $notificacion = Notificaciones::create($data);

            return response()->json([
                'success' => true,
                'message' => 'Notificación creada correctamente',
                'data' => $notificacion
            ], 201);
        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => 'Ocurrió un error al crear la notificación',
                'detalle' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $notificacion = Notificaciones::find($id);

        if (!$notificacion) {

            return response()->json([
                'success' => false,
                'message' => 'Notificación no encontrada'
            ], 404);
        }

        $data = $request->validate([
            'receptorId' => 'required|integer|min:1',
            'remitenteId' => 'required|integer|min:1',
            'trabajoId' => 'nullable|integer|min:1',
            'tipo' => 'nullable|string|in:' . implode(',', Notificaciones::tiposValidos()),
            'asunto' => 'nullable|string|max:255',
            'mensaje' => 'required|string',
        ]);

        $data['tipo'] = $data['tipo'] ?? 'notificacion';

        try {

            $notificacion->update($data);

            return response()->json([
                'success' => true,
                'message' => 'Notificación actualizada correctamente',
                'data' => $notificacion
            ]);
        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => 'Ocurrió un error al actualizar la notificación',
                'detalle' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        $notificacion = Notificaciones::find($id);

        if (!$notificacion) {

            return response()->json([
                'success' => false,
                'message' => 'Notificación no encontrada'
            ], 404);
        }

        try {

            $notificacion->delete();

            return response()->json([
                'success' => true,
                'message' => 'Notificación eliminada correctamente'
            ]);
        } catch (QueryException $e) {

            return response()->json([
                'success' => false,
                'message' => 'No se puede eliminar la notificación debido a un conflicto en la base de datos',
                'detalle' => $e->getMessage()
            ], 409);
        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => 'Ocurrió un error al eliminar la notificación',
                'detalle' => $e->getMessage()
            ], 500);
        }
    }
}
