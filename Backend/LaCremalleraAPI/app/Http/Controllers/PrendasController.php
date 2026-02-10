<?php

namespace App\Http\Controllers;

use App\Models\Prendas;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;

/**
 * @OA\Tag(
 *     name="Prendas",
 *     description="Operaciones relacionadas con prendas"
 * )
 */
class PrendasController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/prendas",
     *     summary="Obtener todas las prendas",
     *     tags={"Prendas"},
     *     @OA\Response(
     *         response=200,
     *         description="Listado de prendas",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Prenda"))
     *     )
     * )
     */
    public function index()
    {
        return response()->json(Prendas::all());
    }

    /**
     * @OA\Get(
     *     path="/api/prendas/{id}",
     *     summary="Obtener una prenda por id",
     *     tags={"Prendas"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de la prenda",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Prenda encontrada",
     *         @OA\JsonContent(ref="#/components/schemas/Prenda")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Prenda no encontrada",
     *         @OA\JsonContent(type="object", @OA\Property(property="error", type="string"))
     *     )
     * )
     */
    public function show($id)
    {
        $prenda = Prendas::find($id);
        if (!$prenda) return response()->json(['error' => 'Prenda no encontrada'], 404);
        return response()->json($prenda);
    }

    /**
     * @OA\Get(
     *     path="/api/prendas/usuario/{usuarioId}",
     *     summary="Obtener prendas por usuarioId",
     *     tags={"Prendas"},
     *     @OA\Parameter(
     *         name="usuarioId",
     *         in="path",
     *         required=true,
     *         description="ID del usuario",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Listado de prendas del usuario",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Prenda"))
     *     )
     * )
     */
    public function showByUsuario($usuarioId)
    {
        $prendas = Prendas::where('usuarioId', $usuarioId)->get();
        return response()->json($prendas);
    }

    /**
     * @OA\Post(
     *     path="/api/prendas",
     *     summary="Crear una prenda",
     *     tags={"Prendas"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Prenda")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Prenda creada",
     *         @OA\JsonContent(ref="#/components/schemas/Prenda")
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
            'usuarioId' => 'required|integer',
            'tipo' => 'required|string',
            'descripcion' => 'nullable|string',
            'color' => 'nullable|string',
            'talla' => 'nullable|string',
        ]);

        $prenda = Prendas::create($validated);
        return response()->json($prenda, 201);
    }

    /**
     * @OA\Put(
     *     path="/api/prendas/{id}",
     *     summary="Actualizar una prenda",
     *     tags={"Prendas"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de la prenda",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Prenda")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Prenda actualizada",
     *         @OA\JsonContent(ref="#/components/schemas/Prenda")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Prenda no encontrada",
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
        $prenda = Prendas::find($id);
        if (!$prenda) return response()->json(['error' => 'Prenda no encontrada'], 404);

        $validated = $request->validate([
            'usuarioId' => 'required|integer',
            'tipo' => 'required|string',
            'descripcion' => 'nullable|string',
            'color' => 'nullable|string',
            'talla' => 'nullable|string',
        ]);

        $prenda->update($validated);
        return response()->json($prenda);
    }

    /**
     * @OA\Delete(
     *     path="/api/prendas/{id}",
     *     summary="Eliminar una prenda",
     *     tags={"Prendas"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de la prenda",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Prenda eliminada",
     *         @OA\JsonContent(type="object", @OA\Property(property="message", type="string"))
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Prenda no encontrada",
     *         @OA\JsonContent(type="object", @OA\Property(property="error", type="string"))
     *     )
     * )
     */
    public function destroy($id)
    {
        $prenda = Prendas::find($id);
        if (!$prenda) return response()->json(['error' => 'Prenda no encontrada'], 404);

        $prenda->delete();
        return response()->json(['message' => 'Prenda eliminada']);
    }
}
