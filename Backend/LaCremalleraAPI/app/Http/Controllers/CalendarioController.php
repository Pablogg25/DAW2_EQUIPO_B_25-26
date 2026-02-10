<?php

namespace App\Http\Controllers;

use App\Models\Calendario;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;

/**
 * @OA\Tag(
 *     name="Calendario",
 *     description="Operaciones relacionadas con el calendario"
 * )
 */
class CalendarioController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/calendarios",
     *     summary="Obtener todos los calendarios",
     *     tags={"Calendarios"},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de calendarios",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Calendario")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Error en la solicitud"
     *     )
     * )
     */
    public function index()
    {
        return response()->json(Calendario::all());
    }

    /**
     * @OA\Get(
     *     path="/api/calendarios/{id}",
     *     summary="Obtener calendario por ID",
     *     tags={"Calendarios"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID del calendario",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Calendario encontrado",
     *         @OA\JsonContent(ref="#/components/schemas/Calendario")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Calendario no encontrado"
     *     )
     * )
     */
    public function show($id)
    {
        $Calendario = Calendario::find($id);
        if (!$Calendario) {
            return response()->json(['error' => 'Calendario no encontrado'], 404);
        }
        return response()->json($Calendario);
    }

    /**
     * @OA\Get(
     *     path="/api/calendarios/usuario/{usuarioId}",
     *     summary="Obtener calendarios por usuario",
     *     tags={"Calendarios"},
     *     @OA\Parameter(
     *         name="usuarioId",
     *         in="path",
     *         required=true,
     *         description="ID del usuario",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Lista de calendarios del usuario",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Calendario")
     *         )
     *     )
     * )
     */
    public function byUsuario($usuarioId)
    {
        $Calendarios = Calendario::where('usuarioId', $usuarioId)->get();
        return response()->json($Calendarios);
    }

    /**
     * @OA\Get(
     *     path="/api/calendarios/empleado/{empleadoId}",
     *     summary="Obtener calendarios por empleado",
     *     tags={"Calendarios"},
     *     @OA\Parameter(
     *         name="empleadoId",
     *         in="path",
     *         required=true,
     *         description="ID del empleado",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Lista de calendarios del empleado",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Calendario")
     *         )
     *     )
     * )
     */
    public function byEmpleado($empleadoId)
    {
        $Calendarios = Calendario::where('empleadoId', $empleadoId)->get();
        return response()->json($Calendarios);
    }

    /**
     * @OA\Get(
     *     path="/api/calendarios/trabajo/{trabajoId}",
     *     summary="Obtener calendarios por trabajo",
     *     tags={"Calendarios"},
     *     @OA\Parameter(
     *         name="trabajoId",
     *         in="path",
     *         required=true,
     *         description="ID del trabajo",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Lista de calendarios del trabajo",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Calendario")
     *         )
     *     )
     * )
     */
    public function byTrabajo($trabajoId)
    {
        $Calendarios = Calendario::where('trabajoId', $trabajoId)->get();
        return response()->json($Calendarios);
    }

    /**
     * @OA\Post(
     *     path="/api/calendarios",
     *     summary="Crear un nuevo calendario",
     *     tags={"Calendarios"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             required={"titulo", "fecha_inicio", "fecha_fin", "usuarioId"},
     *             @OA\Property(property="titulo", type="string", example="Reunión de equipo"),
     *             @OA\Property(property="descripcion", type="string", example="Reunión de planificación de proyecto"),
     *             @OA\Property(property="fecha_inicio", type="string", format="date", example="2023-03-01"),
     *             @OA\Property(property="fecha_fin", type="string", format="date", example="2023-03-01"),
     *             @OA\Property(property="usuarioId", type="integer", example=101),
     *             @OA\Property(property="empleadoId", type="integer", example=202),
     *             @OA\Property(property="trabajoId", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Calendario creado",
     *         @OA\JsonContent(ref="#/components/schemas/Calendario")
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Datos inválidos"
     *     )
     * )
     */
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

        $Calendario = Calendario::create($validated);

        return response()->json($Calendario, 201);
    }

    /**
     * @OA\Put(
     *     path="/api/calendarios/{id}",
     *     summary="Actualizar un calendario existente",
     *     tags={"Calendarios"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID del calendario",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             required={"titulo", "fecha_inicio", "fecha_fin", "usuarioId"},
     *             @OA\Property(property="titulo", type="string", example="Reunión de equipo"),
     *             @OA\Property(property="descripcion", type="string", example="Reunión de planificación de proyecto"),
     *             @OA\Property(property="fecha_inicio", type="string", format="date", example="2023-03-01"),
     *             @OA\Property(property="fecha_fin", type="string", format="date", example="2023-03-01"),
     *             @OA\Property(property="usuarioId", type="integer", example=101),
     *             @OA\Property(property="empleadoId", type="integer", example=202),
     *             @OA\Property(property="trabajoId", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Calendario actualizado",
     *         @OA\JsonContent(ref="#/components/schemas/Calendario")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Calendario no encontrado"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Datos inválidos"
     *     )
     * )
     */
    public function update(Request $request, $id)
    {
        $Calendario = Calendario::find($id);
        if (!$Calendario) {
            return response()->json(['error' => 'Calendario no encontrado'], 404);
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

        $Calendario->update($validated);

        return response()->json($Calendario);
    }

    /**
     * @OA\Delete(
     *     path="/api/calendarios/{id}",
     *     summary="Eliminar un calendario",
     *     tags={"Calendarios"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID del calendario",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Calendario eliminado correctamente",
     *         @OA\JsonContent(type="object", @OA\Property(property="message", type="string", example="Calendario eliminado correctamente"))
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Calendario no encontrado"
     *     )
     * )
     */
    public function destroy($id)
    {
        $Calendario = Calendario::find($id);
        if (!$Calendario) {
            return response()->json(['error' => 'Calendario no encontrado'], 404);
        }

        $Calendario->delete();

        return response()->json(['message' => 'Calendario eliminado correctamente']);
    }
}
