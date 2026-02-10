<?php

namespace App\Http\Controllers;

use App\Models\Inventario;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;

/**
 * @OA\Tag(
 *     name="Inventarios",
 *     description="Operaciones relacionadas con inventarios"
 * )
 */
class InventarioController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/inventarios",
     *     summary="Listar todos los inventarios",
     *     tags={"Inventarios"},
     *     @OA\Response(
     *         response=200,
     *         description="Listado de inventarios",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Inventario"))
     *     )
     * )
     */
    public function index()
    {
        return response()->json(Inventario::all());
    }

    /**
     * @OA\Get(
     *     path="/api/inventarios/{id}",
     *     summary="Obtener un inventario por ID",
     *     tags={"Inventarios"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID del inventario",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Inventario encontrado",
     *         @OA\JsonContent(ref="#/components/schemas/Inventario")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Inventario no encontrado",
     *         @OA\JsonContent(type="object", @OA\Property(property="error", type="string"))
     *     )
     * )
     */
    public function show($id)
    {
        $Inventario = Inventario::findOrFail($id);
        return response()->json($Inventario);
    }

    /**
     * @OA\Get(
     *     path="/api/inventarios/bajo-stock",
     *     summary="Obtener inventarios bajo stock",
     *     tags={"Inventarios"},
     *     @OA\Response(
     *         response=200,
     *         description="Listado de inventarios con bajo stock",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Inventario"))
     *     )
     * )
     */
    public function bajoStock()
    {
        $Inventarios = Inventario::whereColumn('cantidad', '<=', 'stock_minimo')->get();
        return response()->json($Inventarios);
    }

    /**
     * @OA\Post(
     *     path="/api/inventarios",
     *     summary="Crear un nuevo inventario",
     *     tags={"Inventarios"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Inventario")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Inventario creado",
     *         @OA\JsonContent(ref="#/components/schemas/Inventario")
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
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'cantidad' => 'nullable|integer|min:0',
            'stock_minimo' => 'nullable|integer|min:0',
        ]);

        $Inventario = Inventario::create($data);

        return response()->json($Inventario, 201);
    }

    /**
     * @OA\Put(
     *     path="/api/inventarios/{id}",
     *     summary="Actualizar un inventario",
     *     tags={"Inventarios"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID del inventario",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Inventario")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Inventario actualizado",
     *         @OA\JsonContent(ref="#/components/schemas/Inventario")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Inventario no encontrado",
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
        $Inventario = Inventario::findOrFail($id);

        $data = $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'cantidad' => 'nullable|integer|min:0',
            'stock_minimo' => 'nullable|integer|min:0',
        ]);

        $Inventario->update($data);

        return response()->json($Inventario);
    }

    /**
     * @OA\Delete(
     *     path="/api/inventarios/{id}",
     *     summary="Eliminar un inventario",
     *     tags={"Inventarios"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID del inventario",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Inventario eliminado",
     *         @OA\JsonContent(type="object", @OA\Property(property="message", type="string"))
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Inventario no encontrado",
     *         @OA\JsonContent(type="object", @OA\Property(property="error", type="string"))
     *     )
     * )
     */
    public function destroy($id)
    {
        $Inventario = Inventario::findOrFail($id);
        $Inventario->delete();

        return response()->json(['message' => 'Inventario eliminado correctamente.']);
    }
}
