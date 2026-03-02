<?php

namespace App\Http\Controllers;

use App\Models\Inventario;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class InventarioController extends Controller
{

    public function index(Request $request)
    {
        try {

            $user = $request->user();

            if (!in_array($user->rol, ['admin', 'empleado'])) {
                return $this->error('No autorizado', 403);
            }

            $query = Inventario::query();

            if ($request->has('nombre')) {
                $query->where(
                    'nombre',
                    'like',
                    '%' . $request->nombre . '%'
                );
            }

            $inventarios = $query->get();

            if (
                $request->has('nombre') &&
                $inventarios->isEmpty()
            ) {
                return $this->error(
                    'No se encontró producto',
                    404
                );
            }

            return $this->success($inventarios);
        } catch (\Throwable $e) {

            return $this->error(
                'Error al listar inventario',
                500,
                $e->getMessage()
            );
        }
    }


    public function show(Request $request, $id)
    {
        try {

            $user = $request->user();

            if (!in_array($user->rol, ['admin', 'empleado'])) {
                return $this->error(
                    'No autorizado',
                    403
                );
            }

            $inventario = Inventario::find($id);

            if (!$inventario) {
                return $this->error(
                    'Inventario no encontrado',
                    404
                );
            }

            return $this->success($inventario);
        } catch (\Throwable $e) {

            return $this->error(
                'Error al obtener inventario',
                500,
                $e->getMessage()
            );
        }
    }


    public function bajoStock(Request $request)
    {
        try {

            $user = $request->user();

            if (!in_array($user->rol, ['admin', 'empleado'])) {
                return $this->error(
                    'No autorizado',
                    403
                );
            }

            $inventarios = Inventario::whereColumn(
                'cantidad',
                '<=',
                'stock_minimo'
            )->get();

            return $this->success($inventarios);
        } catch (\Throwable $e) {

            return $this->error(
                'Error bajo stock',
                500,
                $e->getMessage()
            );
        }
    }


    public function store(Request $request)
    {
        try {

            $user = $request->user();

            if ($user->rol !== 'admin') {
                return $this->error(
                    'Solo admin puede crear',
                    403
                );
            }

            $data = $request->validate([
                'nombre' => 'required|string|max:255',
                'descripcion' => 'nullable|string|max:500',
                'cantidad' => 'nullable|integer|min:0',
                'stock_minimo' => 'nullable|integer|min:0',
            ]);

            $inventario = Inventario::create($data);

            return $this->success(
                $inventario,
                'Inventario creado',
                201
            );
        } catch (\Throwable $e) {

            return $this->error(
                'Error crear inventario',
                500,
                $e->getMessage()
            );
        }
    }


    public function update(Request $request, $id)
    {
        try {

            $user = $request->user();

            if (!in_array($user->rol, ['admin', 'empleado'])) {
                return $this->error(
                    'No autorizado',
                    403
                );
            }

            $inventario = Inventario::find($id);

            if (!$inventario) {
                return $this->error(
                    'Inventario no encontrado',
                    404
                );
            }

            $data = $request->validate([
                'nombre' => 'required|string|max:255',
                'descripcion' => 'nullable|string|max:500',
                'cantidad' => 'nullable|integer|min:0',
                'stock_minimo' => 'nullable|integer|min:0',
            ]);

            $inventario->update($data);

            return $this->success(
                $inventario,
                'Inventario actualizado'
            );
        } catch (\Throwable $e) {

            return $this->error(
                'Error actualizar',
                500,
                $e->getMessage()
            );
        }
    }


    public function destroy(Request $request, $id)
    {
        try {

            $user = $request->user();

            if ($user->rol !== 'admin') {
                return $this->error(
                    'Solo admin',
                    403
                );
            }

            $inventario = Inventario::find($id);

            if (!$inventario) {
                return $this->error(
                    'Inventario no encontrado',
                    404
                );
            }

            $inventario->delete();

            return $this->success(
                null,
                'Inventario eliminado'
            );
        } catch (QueryException $e) {

            return $this->error(
                'Conflicto BD',
                409,
                $e->getMessage()
            );
        } catch (\Throwable $e) {

            return $this->error(
                'Error eliminar',
                500,
                $e->getMessage()
            );
        }
    }
}