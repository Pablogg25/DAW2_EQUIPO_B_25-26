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

<<<<<<< HEAD
    /**
     * @OA\Put(
     *     path="/api/usuarios/{id}",
     *     summary="Actualizar los datos de un usuario",
     *     tags={"Usuarios"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID del usuario",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"nombre", "username", "email", "rol"},
     *             @OA\Property(property="nombre", type="string", example="Juan Pérez"),
     *             @OA\Property(property="telefono", type="string", example="1234567890"),
     *             @OA\Property(property="email", type="string", example="juan@dominio.com"),
     *             @OA\Property(property="direccion", type="string", example="Calle Ficticia 123"),
     *             @OA\Property(property="username", type="string", example="juan123"),
     *             @OA\Property(property="rol", type="string", example="empleado")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Usuario actualizado",
     *         @OA\JsonContent(type="boolean")
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Error de validación",
     *         @OA\JsonContent(type="boolean")
     *     )
     *     @OA\Response(
     *         response=404,
     *         description="Usuario no encontrado",
     *         @OA\JsonContent(type="boolean")
     *     )
     * )
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre' => 'required',
            'username' => 'required',
            'email' => 'required|email',
            'rol' => 'required|in:cliente,empleado,admin'
        ]);

        $usuario = Usuarios::findOrFail($id);

        $usuario->update([
            'nombre' => $request->nombre,
            'telefono' => $request->telefono,
            'email' => $request->email,
            'direccion' => $request->direccion,
            'username' => $request->username,
            'rol' => $request->rol
        ]);

        return response()->json(true);
    }

    /**
     * @OA\Put(
     *     path="/api/usuarios/{id}/password",
     *     summary="Actualizar la contraseña de un usuario",
     *     tags={"Usuarios"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID del usuario",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"password"},
     *             @OA\Property(property="password", type="string", example="nueva_contraseña")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Contraseña actualizada",
     *         @OA\JsonContent(type="boolean")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Usuario no encontrado",
     *         @OA\JsonContent(type="boolean")
     *     )
     * )
     */
    public function updatePassword(Request $request, $id)
    {
        $request->validate([
            'password' => 'required'
        ]);

        $usuario = Usuarios::findOrFail($id);
        $usuario->password_SHA2 = $request->password;
        $usuario->save();

        return response()->json(true);
    }

    /**
     * @OA\Delete(
     *     path="/api/usuarios/{id}",
     *     summary="Eliminar un usuario por su ID",
     *     tags={"Usuarios"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID del usuario a eliminar",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Usuario eliminado",
     *         @OA\JsonContent(type="boolean")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Usuario no encontrado",
     *         @OA\JsonContent(type="boolean")
     *     )
     * )
     */

    public function destroy($id)
    {
        try {
            $usuario = Usuarios::findOrFail($id);
            $usuario->delete();

            return response()->json([
                'message' => 'Usuario eliminado correctamente.'
            ], 200);
        } catch (QueryException $e) {
            // Esto captura errores de clave foránea o conflictos con la base de datos
            return response()->json([
                'error' => 'No se puede eliminar el usuario porque está relacionado con otros registros.'
            ], 409); // 409 = Conflict

        } catch (\Exception $e) {
            // Captura cualquier otro error inesperado
            return response()->json([
                'error' => 'Ocurrió un error al intentar eliminar el usuario.',
                'detalle' => $e->getMessage()
            ], 500);
        }
    }
=======
>>>>>>> b328e5a (actualizacion de los controladores, con recojida de errores, actualizacion de la rutas)
}
