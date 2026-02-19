<?php

namespace App\Http\Controllers;

use App\Models\Facturas;
use App\Models\Trabajos;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;

/**
 * @OA\Tag(
 *     name="Facturas",
 *     description="Operaciones relacionadas con facturas"
 * )
 */
class FacturasController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/facturas",
     *     summary="Obtener facturas, opcionalmente filtradas por usuario o trabajo",
     *     tags={"Facturas"},
     *     @OA\Parameter(
     *         name="usuarioId",
     *         in="query",
     *         required=false,
     *         description="Filtrar facturas por ID de usuario",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="trabajoId",
     *         in="query",
     *         required=false,
     *         description="Filtrar facturas por ID de trabajo",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Lista de facturas",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Factura")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Error en la solicitud"
     *     )
     * )
     */
    public function index(Request $request)
    {
        // Si se proporciona trabajoId, devolvemos facturas asociadas al trabajo
        if ($request->has('trabajoId')) {
            $trabajo = Trabajos::findOrFail($request->trabajoId);
            return response()->json($trabajo->facturas);
        }

        $query = Facturas::with('trabajos');

        // Filtrar por usuarioId si se pasa
        if ($request->has('usuarioId')) {
            $query->where('usuarioId', $request->usuarioId);
        }

        $facturas = $query->get();

        return response()->json($facturas);
    }

    /**
     * @OA\Get(
     *     path="/api/facturas/{id}",
     *     summary="Obtener una factura por ID",
     *     tags={"Facturas"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de la factura",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Factura encontrada",
     *         @OA\JsonContent(ref="#/components/schemas/Factura")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Factura no encontrada"
     *     )
     * )
     */
    public function show($id)
    {
        $factura = Facturas::with('trabajos')->findOrFail($id);
        return response()->json($factura);
    }

    /**
     * @OA\Post(
     *     path="/api/facturas",
     *     summary="Crear una nueva factura",
     *     tags={"Facturas"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Factura")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Factura creada",
     *         @OA\JsonContent(ref="#/components/schemas/Factura")
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Datos inválidos"
     *     )
     * )
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'usuarioId' => 'required|integer|min:1',
            'fecha' => 'required|date',
        ]);

        $factura = Facturas::create($data);

        return response()->json($factura, 201);
    }

    /**
     * @OA\Put(
     *     path="/api/facturas/{id}",
     *     summary="Actualizar una factura existente",
     *     tags={"Facturas"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de la factura",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Factura")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Factura actualizada",
     *         @OA\JsonContent(ref="#/components/schemas/Factura")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Factura no encontrada"
     *     )
     * )
     */
    public function update(Request $request, $id)
    {
        $factura = Facturas::findOrFail($id);

        $data = $request->validate([
            'usuarioId' => 'required|integer|min:1',
            'fecha' => 'required|date',
            'pagado' => 'nullable|boolean',
            'total_calculado' => 'nullable|numeric',
        ]);

        $factura->update($data);

        return response()->json($factura);
    }

    /**
     * @OA\Delete(
     *     path="/api/facturas/{id}",
     *     summary="Eliminar una factura",
     *     tags={"Facturas"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de la factura",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Factura eliminada",
     *         @OA\JsonContent(type="object", @OA\Property(property="message", type="string"))
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Factura no encontrada"
     *     )
     * )
     */
    public function destroy($id)
    {
        $factura = Facturas::findOrFail($id);
        $factura->trabajos()->detach();
        $factura->delete();

        return response()->json(['message' => 'Factura eliminada correctamente']);
    }

    /**
     * @OA\Post(
     *     path="/api/facturas/{facturaId}/trabajo",
     *     summary="Asociar un trabajo a una factura",
     *     tags={"Facturas"},
     *     @OA\Parameter(
     *         name="facturaId",
     *         in="path",
     *         required=true,
     *         description="ID de la factura",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             required={"trabajoId"},
     *             @OA\Property(property="trabajoId", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Trabajo asociado correctamente",
     *         @OA\JsonContent(type="object", @OA\Property(property="message", type="string"))
     *     )
     * )
     */
    public function asociarTrabajo(Request $request, $facturaId)
    {
        $factura = Facturas::findOrFail($facturaId);

        $request->validate([
            'trabajoId' => 'required|integer|exists:trabajos,trabajoId',
        ]);

        $factura->trabajos()->attach($request->trabajoId);

        return response()->json(['message' => 'Trabajo asociado correctamente']);
    }

    /**
     * @OA\Post(
     *     path="/api/facturas/{facturaId}/trabajo/desasociar",
     *     summary="Desasociar un trabajo de una factura",
     *     tags={"Facturas"},
     *     @OA\Parameter(
     *         name="facturaId",
     *         in="path",
     *         required=true,
     *         description="ID de la factura",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             required={"trabajoId"},
     *             @OA\Property(property="trabajoId", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Trabajo desasociado correctamente",
     *         @OA\JsonContent(type="object", @OA\Property(property="message", type="string"))
     *     )
     * )
     */
    public function desasociarTrabajo(Request $request, $facturaId)
    {
        $factura = Facturas::findOrFail($facturaId);

        $request->validate([
            'trabajoId' => 'required|integer|exists:trabajos,trabajoId',
        ]);

        $factura->trabajos()->detach($request->trabajoId);

        return response()->json(['message' => 'Trabajo desasociado correctamente']);
    }

    /**
     * @OA\Get(
     *     path="/api/facturas/{id}/calcular-total",
     *     summary="Calcular el total de una factura a partir de los trabajos asociados",
     *     tags={"Facturas"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de la factura",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Total calculado de la factura",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="total", type="number", format="float", example=100.50)
     *         )
     *     )
     * )
     */
    public function calcularTotal($id)
    {
        $factura = Facturas::findOrFail($id);
        $total = $factura->trabajos()->sum('precio');

        $factura->total_calculado = $total;
        $factura->save();

        return response()->json(['total' => $total]);
    }
}
