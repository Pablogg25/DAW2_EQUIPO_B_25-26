<?php

namespace App\Http\Controllers;

use App\Models\Trabajos;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;

/**
 * @OA\Tag(
 *     name="Trabajos",
 *     description="Operaciones relacionadas con trabajos"
 * )
 */
class TrabajosController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/trabajos",
     *     summary="Obtener todos los trabajos",
     *     tags={"Trabajos"},
     *     @OA\Response(
     *         response=200,
     *         description="Listado de trabajos",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Trabajo"))
     *     )
     * )
     */
    public function index()
    {
        return response()->json(Trabajos::all());
    }

    /**
     * @OA\Get(
     *     path="/api/trabajos/{id}",
     *     summary="Obtener un trabajo por id",
     *     tags={"Trabajos"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID del trabajo",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Trabajo encontrado",
     *         @OA\JsonContent(ref="#/components/schemas/Trabajo")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Trabajo no encontrado",
     *         @OA\JsonContent(type="object", @OA\Property(property="error", type="string"))
     *     )
     * )
     */
    public function show($id)
    {
        $trabajo = Trabajos::find($id);
        if (!$trabajo) return response()->json(['error' => 'Trabajo no encontrado'], 404);
        return response()->json($trabajo);
    }

    /**
     * @OA\Post(
     *     path="/api/trabajos",
     *     summary="Crear un trabajo",
     *     tags={"Trabajos"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Trabajo")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Trabajo creado",
     *         @OA\JsonContent(ref="#/components/schemas/Trabajo")
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
        $validated = $request->validate([
            'prendaId' => 'required|integer',
            'empleadoId' => 'nullable|integer',
            'descripcion' => 'nullable|string',
            'fecha_inicio' => 'required|date',
            'fecha_entrega' => 'required|date',
            'estado' => 'nullable|in:pendiente,en_proceso,listo,entregado',
            'precio' => 'nullable|numeric|min:0',
        ]);

        $trabajo = Trabajos::create($validated);
        return response()->json($trabajo, 201);
    }

    /**
     * @OA\Put(
     *     path="/api/trabajos/{id}",
     *     summary="Actualizar un trabajo",
     *     tags={"Trabajos"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID del trabajo",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Trabajo")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Trabajo actualizado",
     *         @OA\JsonContent(ref="#/components/schemas/Trabajo")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Trabajo no encontrado",
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
        $trabajo = Trabajos::find($id);
        if (!$trabajo) return response()->json(['error' => 'Trabajo no encontrado'], 404);

        $validated = $request->validate([
            'prendaId' => 'required|integer',
            'empleadoId' => 'nullable|integer',
            'descripcion' => 'nullable|string',
            'fecha_inicio' => 'required|date',
            'fecha_entrega' => 'required|date',
            'estado' => 'nullable|in:pendiente,en_proceso,listo,entregado',
            'precio' => 'nullable|numeric|min:0',
        ]);

        $trabajo->update($validated);
        return response()->json($trabajo);
    }

    /**
     * @OA\Delete(
     *     path="/api/trabajos/{id}",
     *     summary="Eliminar un trabajo",
     *     tags={"Trabajos"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID del trabajo",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Trabajo eliminado",
     *         @OA\JsonContent(type="object", @OA\Property(property="message", type="string"))
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Trabajo no encontrado",
     *         @OA\JsonContent(type="object", @OA\Property(property="error", type="string"))
     *     )
     * )
     */
    public function destroy($id)
    {
        $trabajo = Trabajos::find($id);
        if (!$trabajo) return response()->json(['error' => 'Trabajo no encontrado'], 404);

        $trabajo->delete();
        return response()->json(['message' => 'Trabajo eliminado']);
    }

    /**
     * @OA\Get(
     *     path="/api/trabajos/{id}/consumos",
     *     summary="Obtener consumos de un trabajo",
     *     tags={"Trabajos"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID del trabajo",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Consumables del trabajo",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/ConsumosTrabajo"))
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Trabajo no encontrado",
     *         @OA\JsonContent(type="object", @OA\Property(property="error", type="string"))
     *     )
     * )
     */
    public function consumos($id)
    {
        $trabajo = Trabajos::find($id);
        if (!$trabajo) return response()->json(['error' => 'Trabajo no encontrado'], 404);

        return response()->json($trabajo->consumos);
    }

    /**
     * @OA\Post(
     *     path="/api/trabajos/{id}/consumos",
     *     summary="Asociar consumo a un trabajo",
     *     tags={"Trabajos"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID del trabajo",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/ConsumosTrabajo")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Consumo asociado",
     *         @OA\JsonContent(ref="#/components/schemas/ConsumosTrabajo")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Trabajo no encontrado",
     *         @OA\JsonContent(type="object", @OA\Property(property="error", type="string"))
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Datos no válidos",
     *         @OA\JsonContent(type="object", @OA\Property(property="error", type="string"))
     *     )
     * )
     */
    public function asociarConsumo(Request $request, $id)
    {
        $trabajo = Trabajos::find($id);
        if (!$trabajo) return response()->json(['error' => 'Trabajo no encontrado'], 404);

        $validated = $request->validate([
            'itemId' => 'required|integer',
            'cantidad_usada' => 'nullable|integer|min:0',
        ]);

        $consumo = $trabajo->consumos()->create($validated);
        return response()->json($consumo, 201);
    }
}
