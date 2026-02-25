<?php

namespace App\Http\Controllers;

use App\Models\Usuarios;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\QueryException;

class UsuariosController extends Controller
{

    public function index(Request $request)
    {
        $auth = $request->user();

        // Solo Admin o Empleado pueden ver lista
        if (!in_array($auth->rol, ['admin', 'empleado'])) {
            return response()->json([
                'success' => false,
                'message' => 'No autorizado'
            ], 403);
        }

        $query = Usuarios::select(
            'usuarioId',
            'nombre',
            'telefono',
            'email',
            'direccion',
            'username',
            'rol',
            'fecha_registro'
        );

        if ($request->has('username')) {
            $query->where('username', $request->username);
        }

        $usuarios = $query->get();

        if ($request->has('username') && $usuarios->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Usuario no encontrado'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $usuarios
        ]);
    }

    public function show(Request $request, $id)
    {
        $auth = $request->user();

        $usuario = Usuarios::select(
            'usuarioId',
            'nombre',
            'telefono',
            'email',
            'direccion',
            'username',
            'rol',
            'fecha_registro'
        )->find($id);

        if (!$usuario) {
            return response()->json([
                'success' => false,
                'message' => 'Usuario no encontrado'
            ], 404);
        }

        // Cliente solo puede ver su perfil
        if ($auth->rol === 'cliente' && $auth->usuarioId != $id) {
            return response()->json([
                'success' => false,
                'message' => 'No autorizado'
            ], 403);
        }

        // Empleado solo puede ver datos no sensibles (ya filtrados)
        return response()->json([
            'success' => true,
            'data' => $usuario
        ]);
    }

    public function store(Request $request)
    {
        $auth = $request->user();

        // Solo admin puede crear empleados o admins
        if ($auth->rol !== 'admin' && $request->rol !== 'cliente') {
            return response()->json([
                'success' => false,
                'message' => 'No puedes crear empleados o administradores'
            ], 403);
        }

        $request->validate([
            'nombre' => 'required|string|max:255',
            'username' => 'required|string|unique:usuarios,username',
            'email' => 'required|email|unique:usuarios,email',
            'password' => 'required|string|min:6',
            'rol' => 'nullable|in:cliente,empleado,admin'
        ]);

        try {

            $usuario = new Usuarios();
            $usuario->nombre = $request->nombre;
            $usuario->telefono = $request->telefono;
            $usuario->email = $request->email;
            $usuario->direccion = $request->direccion;
            $usuario->username = $request->username;
            $usuario->rol = $request->rol ?? 'cliente';
            $usuario->password = $request->password;

            $usuario->save();

            return response()->json([
                'success' => true,
                'message' => 'Usuario creado correctamente',
                'data' => $usuario
            ], 201);

        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => 'Ocurrió un error al crear el usuario',
                'detalle' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $auth = $request->user();

        $usuario = Usuarios::find($id);

        if (!$usuario) {
            return response()->json([
                'success' => false,
                'message' => 'Usuario no encontrado'
            ], 404);
        }

        // Cliente solo puede editar su perfil
        if ($auth->rol === 'cliente' && $auth->usuarioId != $id) {
            return response()->json([
                'success' => false,
                'message' => 'No autorizado'
            ], 403);
        }

        // Empleado solo puede editar su perfil
        if ($auth->rol === 'empleado' && $auth->usuarioId != $id) {
            return response()->json([
                'success' => false,
                'message' => 'No puedes editar otros usuarios'
            ], 403);
        }

        // Solo admin puede cambiar roles
        if ($auth->rol !== 'admin' && $request->has('rol')) {
            return response()->json([
                'success' => false,
                'message' => 'No puedes cambiar roles'
            ], 403);
        }

        $request->validate([
            'nombre' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:usuarios,username,' . $id . ',usuarioId',
            'email' => 'required|email|max:255|unique:usuarios,email,' . $id . ',usuarioId',
            'rol' => 'in:cliente,empleado,admin'
        ]);

        try {

            $usuario->update([
                'nombre' => $request->nombre,
                'telefono' => $request->telefono,
                'email' => $request->email,
                'direccion' => $request->direccion,
                'username' => $request->username,
                'rol' => $auth->rol === 'admin' ? $request->rol : $usuario->rol
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Usuario actualizado correctamente',
                'data' => $usuario
            ]);

        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => 'Ocurrió un error al actualizar el usuario',
                'detalle' => $e->getMessage()
            ], 500);
        }
    }

    public function updatePassword(Request $request, $id)
    {
        $auth = $request->user();

        $usuario = Usuarios::find($id);

        if (!$usuario) {
            return response()->json([
                'success' => false,
                'message' => 'Usuario no encontrado'
            ], 404);
        }

        // Solo el dueño o admin
        if ($auth->rol !== 'admin' && $auth->usuarioId != $id) {
            return response()->json([
                'success' => false,
                'message' => 'No autorizado'
            ], 403);
        }

        try {

            $request->validate([
                'password' => 'required|string|min:6'
            ]);

            $usuario->password = $request->password;
            $usuario->save();

            return response()->json([
                'success' => true,
                'message' => 'Contraseña actualizada correctamente'
            ]);

        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => 'Ocurrió un error al actualizar la contraseña',
                'detalle' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy(Request $request, $id)
    {
        $auth = $request->user();

        if ($auth->rol !== 'admin') {
            return response()->json([
                'success' => false,
                'message' => 'Solo admin puede eliminar usuarios'
            ], 403);
        }

        try {

            $usuario = Usuarios::find($id);

            if (!$usuario) {
                return response()->json([
                    'success' => false,
                    'message' => 'Usuario no encontrado'
                ], 404);
            }

            $usuario->delete();

            return response()->json([
                'success' => true,
                'message' => 'Usuario eliminado correctamente'
            ]);

        } catch (QueryException $e) {

            return response()->json([
                'success' => false,
                'message' => 'No se puede eliminar el usuario porque está relacionado con otros registros'
            ], 409);

        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => 'Ocurrió un error al eliminar el usuario',
                'detalle' => $e->getMessage()
            ], 500);
        }
    }

    public function checkPassword(Request $request)
    {
        try {

            $request->validate([
                'username' => 'required|string',
                'password' => 'required|string'
            ]);

            $usuario = Usuarios::where('username', $request->username)->first();

            if (!$usuario) {
                return response()->json([
                    'success' => false,
                    'valid' => false,
                    'message' => 'Usuario no encontrado'
                ], 404);
            }

            $valid = Hash::check($request->password, $usuario->password);

            return response()->json([
                'success' => true,
                'valid' => $valid,
                'rol' => $usuario->rol,
                'usuarioId' => $usuario->usuarioId
            ]);

        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => 'Error en login',
                'detalle' => $e->getMessage()
            ], 500);
        }
    }
}