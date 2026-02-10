<?php

namespace App\Http\Controllers;

use App\Models\Notificaciones;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;

/**
 * @OA\Tag(
 *     name="Notificaciones",
 *     description="Operaciones relacionadas con notificaciones"
 * )
 */
class NotificacionesController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/notificaciones",
     *     summary="Listar todas las notificaciones",
     *     tags={"Notificaciones"},
     *     @OA\Response(
     *         response=200,
     *         description="Listado de notificaciones",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Notificacion"))
     *     )
     * )
     */
    public function index()
    {
        return response()->json(Notificaciones::all());
    }

    /**
     * @OA\Get(
     *     path="/api/notificaciones/receptor/{receptorId}",
     *     summary="Obtener notificaciones por receptorId",
     *     tags={"Notificaciones"},
     *     @OA\Parameter(
     *         name="receptorId",
     *         in="path",
     *         required=true,
     *         description="ID del receptor",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Listado de notificaciones del receptor",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Notificacion"))
     *     )
     * )
     */
    public function byReceptor($receptorId)
    {
        return response()->json(Notificaciones::where('receptorId', $receptorId)->get());
    }

    /**
     * @OA\Get(
     *     path="/api/notificaciones/remitente/{remitenteId}",
     *     summary="Obtener notificaciones por remitenteId",
     *     tags={"Notificaciones"},
     *     @OA\Parameter(
     *         name="remitenteId",
     *         in="path",
     *         required=true,
     *         description="ID del remitente",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Listado de notificaciones del remitente",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Notificacion"))
     *     )
     * )
     */
    public function byRemitente($remitenteId)
    {
        return response()->json(Notificaciones::where('remitenteId', $remitenteId)->get());
    }

    /**
     * @OA\Get(
     *     path="/api/notificaciones/trabajo/{trabajoId}",
     *     summary="Obtener notificaciones por trabajoId",
     *     tags={"Notificaciones"},
     *     @OA\Parameter(
     *         name="trabajoId",
     *         in="path",
     *         required=true,
     *         description="ID del trabajo",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Listado de notificaciones asociadas a un trabajo",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Notificacion"))
     *     )
     * )
     */
    public function byTrabajo($trabajoId)
    {
        return response()->json(Notificaciones::where('trabajoId', $trabajoId)->get());
    }

    /**
     * @OA\Post(
     *     path="/api/notificaciones",
     *     summary="Crear una nueva notificación",
     *     tags={"Notificaciones"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Notificacion")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Notificación creada",
     *         @OA\JsonContent(ref="#/components/schemas/Notificacion")
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Datos no válidos",
     *         @OA\JsonContent(type="object", @OA\Property(property="error", type="string"))
     *     )
     * )
     */
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

        // Establecer valor por defecto de tipo
        if (!isset($data['tipo'])) {
            $data['tipo'] = 'notificacion';
        }

        $data['fecha_envio'] = now();

        $notificacion = Notificaciones::create($data);

        return response()->json($notificacion, 201);
    }

    /**
     * @OA\Put(
     *     path="/api/notificaciones/{id}",
     *     summary="Actualizar una notificación",
     *     tags={"Notificaciones"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de la notificación",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Notificacion")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Notificación actualizada",
     *         @OA\JsonContent(ref="#/components/schemas/Notificacion")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Notificación no encontrada",
     *         @OA\JsonContent(type="object", @OA\Property(property="error", type="string"))
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Datos no válidos",
     *         @OA\JsonContent(type="object", @OA\Property(property="error", type="string"))
     *     )
     * )
     */
    public function update(Request $request, $id)
    {
        $notificacion = Notificaciones::findOrFail($id);

        $data = $request->validate([
            'receptorId' => 'required|integer|min:1',
            'remitenteId' => 'required|integer|min:1',
            'trabajoId' => 'nullable|integer|min:1',
            'tipo' => 'nullable|string|in:' . implode(',', Notificaciones::tiposValidos()),
            'asunto' => 'nullable|string|max:255',
            'mensaje' => 'required|string',
        ]);

        if (!isset($data['tipo'])) {
            $data['tipo'] = 'notificacion';
        }

        $notificacion->update($data);

        return response()->json($notificacion);
    }

    /**
     * @OA\Delete(
     *     path="/api/notificaciones/{id}",
     *     summary="Eliminar una notificación",
     *     tags={"Notificaciones"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de la notificación",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Notificación eliminada",
     *         @OA\JsonContent(type="object", @OA\Property(property="message", type="string"))
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Notificación no encontrada",
     *         @OA\JsonContent(type="object", @OA\Property(property="error", type="string"))
     *     )
     * )
     */
    public function destroy($id)
    {
        $notificacion = Notificaciones::findOrFail($id);
        $notificacion->delete();

        return response()->json(['message' => 'Notificación eliminada correctamente.']);
    }
}
