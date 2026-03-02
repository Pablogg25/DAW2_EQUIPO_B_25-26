<?php

namespace App\Http\Controllers;

use App\Models\Calendario;
use Illuminate\Http\Request;

class CalendarioController extends Controller
{

    public function index(Request $request)
    {
        try {

            $user = $request->user();

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

            // ownership

            if ($user->rol === 'cliente') {
                $query->where(
                    'usuarioId',
                    $user->usuarioId
                );
            }

            if ($user->rol === 'empleado') {
                $query->where(function ($q) use ($user) {
                    $q->where(
                        'empleadoId',
                        $user->usuarioId
                    );
                });
            }

            $calendarios = $query->get();

            if ($calendarios->isEmpty()) {
                return $this->error(
                    'No se encontraron eventos',
                    404
                );
            }

            return $this->success($calendarios);
        } catch (\Throwable $e) {

            return $this->error(
                'Error listar eventos',
                500,
                $e->getMessage()
            );
        }
    }


    public function show(Request $request, $id)
    {
        try {

            $user = $request->user();

            $calendario = Calendario::find($id);

            if (!$calendario) {
                return $this->error(
                    'Evento no encontrado',
                    404
                );
            }

            if (
                $user->rol === 'cliente' &&
                $calendario->usuarioId != $user->usuarioId
            ) {
                return $this->error(
                    'No autorizado',
                    403
                );
            }

            return $this->success($calendario);
        } catch (\Throwable $e) {

            return $this->error(
                'Error obtener evento',
                500,
                $e->getMessage()
            );
        }
    }


    public function store(Request $request)
    {
        try {

            $user = $request->user();

            $validated = $request->validate([
                'titulo' => 'required|string',
                'descripcion' => 'nullable|string',
                'fecha_inicio' => 'required|date',
                'fecha_fin' => 'required|date',
                'usuarioId' => 'required|integer',
                'empleadoId' => 'nullable|integer',
                'trabajoId' => 'nullable|integer',
            ]);

            if ($user->rol === 'cliente') {
                $validated['usuarioId'] =
                    $user->usuarioId;
            }

            if ($user->rol === 'empleado') {
                $validated['empleadoId'] =
                    $user->usuarioId;
            }

            $calendario = Calendario::create(
                $validated
            );

            return $this->success(
                $calendario,
                'Evento creado',
                201
            );
        } catch (\Throwable $e) {

            return $this->error(
                'Error crear evento',
                500,
                $e->getMessage()
            );
        }
    }


    public function update(Request $request, $id)
    {
        try {

            $user = $request->user();

            if ($user->rol === 'cliente') {
                return $this->error(
                    'Cliente no puede actualizar',
                    403
                );
            }

            $calendario = Calendario::find($id);

            if (!$calendario) {
                return $this->error(
                    'Evento no encontrado',
                    404
                );
            }

            if (
                $user->rol === 'empleado' &&
                $calendario->empleadoId != $user->usuarioId
            ) {
                return $this->error(
                    'No autorizado',
                    403
                );
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

            $calendario->update($validated);

            return $this->success(
                $calendario,
                'Evento actualizado'
            );
        } catch (\Throwable $e) {

            return $this->error(
                'Error actualizar evento',
                500,
                $e->getMessage()
            );
        }
    }


    public function destroy(Request $request, $id)
    {
        try {

            $user = $request->user();

            $calendario = Calendario::find($id);

            if (!$calendario) {
                return $this->error(
                    'Evento no encontrado',
                    404
                );
            }

            if ($user->rol === 'cliente') {
                return $this->error(
                    'No autorizado',
                    403
                );
            }

            if (
                $user->rol === 'empleado' &&
                $calendario->empleadoId != $user->usuarioId
            ) {
                return $this->error(
                    'No autorizado',
                    403
                );
            }

            $calendario->delete();

            return $this->success(
                null,
                'Evento eliminado'
            );
        } catch (\Throwable $e) {

            return $this->error(
                'Error eliminar evento',
                500,
                $e->getMessage()
            );
        }
    }
}