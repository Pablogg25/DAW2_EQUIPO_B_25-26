<?php

namespace App\Http\Controllers;

use App\Models\Prendas;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;

class PrendasController extends Controller
{

    public function index(Request $request)
    {
        try {

            $user = $request->user();

            if (!in_array($user->rol, ['admin', 'empleado'])) {
                return $this->error('No autorizado', 403);
            }

            $query = Prendas::query();

            if ($request->has('usuarioId')) {
                $query->where('usuarioId', $request->usuarioId);
            }

            $prendas = $query->get();

            if ($request->has('usuarioId') && $prendas->isEmpty()) {
                return $this->error(
                    'No se encontraron prendas',
                    404
                );
            }

            return $this->success($prendas);

        } catch (\Throwable $e) {

            return $this->error(
                'Error al listar prendas',
                500,
                $e->getMessage()
            );
        }
    }


    public function show(Request $request, $id)
    {
        try {

            $user = $request->user();

            $prenda = Prendas::find($id);

            if (!$prenda) {
                return $this->error(
                    'Prenda no encontrada',
                    404
                );
            }

            if (
                $user->rol === 'cliente' &&
                $prenda->usuarioId != $user->usuarioId
            ) {
                return $this->error(
                    'No autorizado',
                    403
                );
            }

            return $this->success($prenda);

        } catch (\Throwable $e) {

            return $this->error(
                'Error al obtener prenda',
                500,
                $e->getMessage()
            );
        }
    }


    public function store(Request $request)
    {
        try {

            $user = $request->user();

            if (!in_array($user->rol, ['admin', 'empleado'])) {
                return $this->error(
                    'No autorizado',
                    403
                );
            }

            $validated = $request->validate([
                'usuarioId' => 'required|integer',
                'tipo' => 'required|string|max:255',
                'descripcion' => 'nullable|string|max:500',
                'color' => 'nullable|string|max:50',
                'talla' => 'nullable|string|max:10',
            ]);

            $prenda = Prendas::create($validated);

            return $this->success(
                $prenda,
                'Prenda creada',
                201
            );

        } catch (\Throwable $e) {

            return $this->error(
                'Error al crear prenda',
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

            $prenda = Prendas::find($id);

            if (!$prenda) {
                return $this->error(
                    'Prenda no encontrada',
                    404
                );
            }

            $validated = $request->validate([
                'usuarioId' => 'required|integer',
                'tipo' => 'required|string|max:255',
                'descripcion' => 'nullable|string|max:500',
                'color' => 'nullable|string|max:50',
                'talla' => 'nullable|string|max:10',
            ]);

            $prenda->update($validated);

            return $this->success(
                $prenda,
                'Prenda actualizada'
            );

        } catch (\Throwable $e) {

            return $this->error(
                'Error al actualizar',
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
                    'Solo admin puede eliminar',
                    403
                );
            }

            $prenda = Prendas::find($id);

            if (!$prenda) {
                return $this->error(
                    'Prenda no encontrada',
                    404
                );
            }

            $prenda->delete();

            return $this->success(
                null,
                'Prenda eliminada'
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