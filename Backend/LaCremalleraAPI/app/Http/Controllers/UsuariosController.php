<?php

namespace App\Http\Controllers;

use App\Models\Usuarios;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;

/**
 * @OA\Tag(
 *     name="Usuarios",
 *     description="Operaciones relacionadas con los usuarios"
 * )
 * 
 * @OA\PathItem(
 *     path="/api/Usuarios"
 * )
 */
class UsuariosController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/usuarios",
     *     summary="Obtener todos los usuarios",
     *     tags={"Usuarios"},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de usuarios",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Usuario"))
     *     )
     * )
     */
    public function index()
    {
        return Usuarios::select(
            'usuarioId',
            'nombre',
            'telefono',
            'email',
            'direccion',
            'username',
            'rol',
            'fecha_registro'
        )->get();
    }

    /**
     * @OA\Get(
     *     path="/api/usuarios/username/{username}",
     *     summary="Obtener un usuario por su nombre de usuario",
     *     tags={"Usuarios"},
     *     @OA\Parameter(
     *         name="username",
     *         in="path",
     *         required=true,
     *         description="Nombre de usuario",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Usuario encontrado",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Usuario"))
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Usuario no encontrado",
     *         @OA\JsonContent(type="boolean")
     *     )
     * )
     */
    public function showByUsername($username)
    {
        return Usuarios::where('username', $username)
            ->select(
                'usuarioId',
                'nombre',
                'telefono',
                'email',
                'direccion',
                'username',
                'rol',
                'fecha_registro'
            )
            ->get();
    }

    /**
     * @OA\Get(
     *     path="/api/usuarios/{id}",
     *     summary="Obtener un usuario por su ID",
     *     tags={"Usuarios"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID del usuario",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Usuario encontrado",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Usuario"))
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Usuario no encontrado",
     *         @OA\JsonContent(type="boolean")
     *     )
     * )
     */
    public function show($id)
    {
        return Usuarios::where('usuarioId', $id)
            ->select(
                'usuarioId',
                'nombre',
                'telefono',
                'email',
                'direccion',
                'username',
                'rol',
                'fecha_registro'
            )
            ->get();
    }

    /**
     * @OA\Post(
     *     path="/api/login",
     *     summary="Verificar si el usuario y la contraseña son correctos",
     *     tags={"Usuarios"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"username", "password"},
     *             @OA\Property(property="username", type="string", example="juan123"),
     *             @OA\Property(property="password", type="string", example="mi_contraseña")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Resultado de la autenticación",
     *         @OA\JsonContent(type="boolean")
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Credenciales incorrectas",
     *         @OA\JsonContent(type="boolean")
     *     )
     * )
     */
    public function checkPassword(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required'
        ]);

        $usuario = Usuarios::where('username', $request->username)->first();

        if (!$usuario) {
            return response()->json(false);
        }

        return response()->json(
            hash('sha224', $request->password) === $usuario->password_SHA2
        );
    }

    /**
     * @OA\Post(
     *     path="/api/usuarios",
     *     summary="Crear un nuevo usuario",
     *     tags={"Usuarios"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"nombre", "username", "email", "password", "rol"},
     *             @OA\Property(property="nombre", type="string", example="Juan Pérez"),
     *             @OA\Property(property="telefono", type="string", example="1234567890"),
     *             @OA\Property(property="email", type="string", example="juan@dominio.com"),
     *             @OA\Property(property="direccion", type="string", example="Calle Ficticia 123"),
     *             @OA\Property(property="username", type="string", example="juan123"),
     *             @OA\Property(property="password", type="string", example="mi_contraseña"),
     *             @OA\Property(property="rol", type="string", example="cliente")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Usuario creado",
     *         @OA\JsonContent(type="boolean")
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Error de validación",
     *         @OA\JsonContent(type="boolean")
     *     )
     * )
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required',
            'username' => 'required|unique:usuarios',
            'email' => 'required|email|unique:usuarios',
            'password' => 'required',
            'rol' => 'in:cliente,empleado,admin'
        ]);

        $usuario = new Usuarios();
        $usuario->nombre = $request->nombre;
        $usuario->telefono = $request->telefono;
        $usuario->email = $request->email;
        $usuario->direccion = $request->direccion;
        $usuario->username = $request->username;
        $usuario->password_SHA2 = $request->password;
        $usuario->rol = $request->rol ?? 'cliente';
        $usuario->save();

        return response()->json(true);
    }

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
}
