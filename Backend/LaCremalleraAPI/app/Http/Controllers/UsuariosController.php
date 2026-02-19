<?php

namespace App\Http\Controllers;

use App\Models\Usuarios;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UsuariosController extends Controller
{

    public function index(Request $request)
    {
        $query = Usuarios::select( // Verificar cual dato queremos mostrar
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
        $usuario = Usuarios::select( // Verificar cual dato queremos mostrar
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
            'username' => 'required|string|max:255|unique:usuarios',
            'email' => 'required|email|max:255|unique:usuarios',
            'password' => 'required|string|min:6',
            'rol' => 'in:cliente,empleado,admin'
        ]);

        try {

            $usuario = new Usuarios();
            $usuario->nombre = $request->nombre;
            $usuario->telefono = $request->telefono;
            $usuario->email = $request->email;
            $usuario->direccion = $request->direccion;
            $usuario->username = $request->username;
            $usuario->password = Hash::make($request->password);
            $usuario->rol = $request->rol ?? 'cliente';
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

            $usuario = Usuarios::findOrFail($id);

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
        $request->validate([
            'password' => 'required|string|min:6'
        ]);

        try {

            $usuario = Usuarios::findOrFail($id);
            $usuario->password = Hash::make($request->password);
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

            $usuario = Usuarios::findOrFail($id);

            // Opcional: verificar relaciones antes de borrar
            if ($usuario->trabajos()->exists()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No se puede eliminar el usuario porque está relacionado con trabajos.'
                ], 409);
            }

            $usuario->delete();

            return response()->json([
                'success' => true,
                'message' => 'Usuario eliminado correctamente'
            ]);

        } catch (QueryException $e) {

            return response()->json([
                'success' => false,
                'message' => 'No se puede eliminar el usuario debido a un conflicto de base de datos',
                'detalle' => $e->getMessage()
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
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string'
        ]);

        $usuario = Usuarios::where('username', $request->username)->first();

        if (!$usuario) {

            return response()->json([
                'success' => false,
                'message' => 'Usuario no encontrado'
            ], 404);

        }

        $match = Hash::check($request->password, $usuario->password);

        return response()->json([
            'success' => true,
            'valid' => $match
        ]);
        
    }

}
