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

    public function show($id)
    {
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

        return response()->json([
            'success' => true,
            'data' => $usuario
        ]);
    }

    public function store(Request $request)
    {
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
            
            // El mutator del modelo lo encripta automáticamente bcrypt
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
        $request->validate([
            'nombre' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:usuarios,username,' . $id . ',usuarioId',
            'email' => 'required|email|max:255|unique:usuarios,email,' . $id . ',usuarioId',
            'rol' => 'required|in:cliente,empleado,admin'
        ]);

        try {

            $usuario = Usuarios::find($id);

            if (!$usuario) {
                return response()->json([
                    'success' => false,
                    'message' => 'Usuario no encontrado'
                ], 404);
            }

            $usuario->update([
                'nombre' => $request->nombre,
                'telefono' => $request->telefono,
                'email' => $request->email,
                'direccion' => $request->direccion,
                'username' => $request->username,
                'rol' => $request->rol
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
        try {

            $usuario = Usuarios::find($id);

            if (!$usuario) {
                return response()->json([
                    'success' => false,
                    'message' => 'Usuario no encontrado'
                ], 404);
            }

            $request->validate([
                'password' => 'required|string|min:6'
            ]);

            $usuario->password = $request->password;

            // Mutator encripta automáticamente bcrypt
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

    public function destroy($id)
    {
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
                'valid' => $valid
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
