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
        try {

            $user = $request->user();

            if ($user->rol !== 'admin') {
                return $this->error('No autorizado', 403);
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
                return $this->error('Usuario no encontrado', 404);
            }

            return $this->success($usuarios);
        } catch (\Throwable $e) {

            return $this->error(
                'Error al obtener usuarios',
                500,
                $e->getMessage()
            );
        }
    }



    public function show(Request $request, $id)
    {
        try {

            $user = $request->user();

            $usuario = Usuarios::find($id);

            if (!$usuario) {
                return $this->error('Usuario no encontrado', 404);
            }

            if (
                $user->rol === 'cliente' &&
                $user->usuarioId != $id
            ) {
                return $this->error('No autorizado', 403);
            }

            return $this->success($usuario);
        } catch (\Throwable $e) {

            return $this->error(
                'Error al obtener usuario',
                500,
                $e->getMessage()
            );
        }
    }



    public function store(Request $request)
    {
        try {

            $user = $request->user();

            $request->validate([
                'nombre' => 'required|string|max:255',
                'username' => 'required|string|unique:usuarios,username',
                'email' => 'required|email|unique:usuarios,email',
                'password' => 'required|string|min:6',
                'rol' => 'nullable|in:cliente,empleado,admin'
            ]);

            $rol = $request->rol ?? 'cliente';

            // empleado solo puede crear clientes
            if ($user->rol === 'empleado' && $rol !== 'cliente') {
                return $this->error(
                    'Empleado solo puede crear clientes',
                    403
                );
            }

            $usuario = new Usuarios();

            $usuario->nombre = $request->nombre;
            $usuario->telefono = $request->telefono;
            $usuario->email = $request->email;
            $usuario->direccion = $request->direccion;
            $usuario->username = $request->username;
            $usuario->rol = $rol;
            $usuario->password = $request->password;

            $usuario->save();

            return $this->success(
                $usuario,
                'Usuario creado',
                201
            );
        } catch (\Throwable $e) {

            return $this->error(
                'Error al crear usuario',
                500,
                $e->getMessage()
            );
        }
    }



    public function update(Request $request, $id)
    {
        try {

            $user = $request->user();

            $usuario = Usuarios::find($id);

            if (!$usuario) {
                return $this->error(
                    'Usuario no encontrado',
                    404
                );
            }

            // cliente solo puede editarse a si mismo
            if (
                $user->rol === 'cliente' &&
                $user->usuarioId != $id
            ) {
                return $this->error(
                    'No autorizado',
                    403
                );
            }

            $request->validate([
                'nombre' => 'required|string|max:255',
                'username' => 'required|string|max:255|unique:usuarios,username,' . $id . ',usuarioId',
                'email' => 'required|email|max:255|unique:usuarios,email,' . $id . ',usuarioId',
                'rol' => 'required|in:cliente,empleado,admin'
            ]);

            $usuario->update([
                'nombre' => $request->nombre,
                'telefono' => $request->telefono,
                'email' => $request->email,
                'direccion' => $request->direccion,
                'username' => $request->username,
                'rol' => $request->rol
            ]);

            return $this->success(
                $usuario,
                'Usuario actualizado'
            );
        } catch (\Throwable $e) {

            return $this->error(
                'Error al actualizar usuario',
                500,
                $e->getMessage()
            );
        }
    }



    public function updatePassword(Request $request, $id)
    {
        try {

            $user = $request->user();

            if (
                $user->usuarioId != $id &&
                $user->rol !== 'admin'
            ) {
                return $this->error(
                    'No autorizado',
                    403
                );
            }

            $usuario = Usuarios::find($id);

            if (!$usuario) {
                return $this->error(
                    'Usuario no encontrado',
                    404
                );
            }

            $request->validate([
                'password' => 'required|string|min:6'
            ]);

            $usuario->password = $request->password;

            $usuario->save();

            return $this->success(
                null,
                'Password actualizada'
            );
        } catch (\Throwable $e) {

            return $this->error(
                'Error al actualizar password',
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

            $usuario = Usuarios::find($id);

            if (!$usuario) {
                return $this->error(
                    'Usuario no encontrado',
                    404
                );
            }

            $usuario->delete();

            return $this->success(
                null,
                'Usuario eliminado'
            );
        } catch (QueryException $e) {

            return $this->error(
                'Relacionado con otros registros',
                409,
                $e->getMessage()
            );
        } catch (\Throwable $e) {

            return $this->error(
                'Error al eliminar',
                500,
                $e->getMessage()
            );
        }
    }

    public function checkPassword(Request $request)
    {
        try {
            // uso login para que el usuario pueda ingresar con su username o email
            $request->validate([
                'login' => 'required',
                'password' => 'required'
            ]);

            $user = Usuarios::where('email', $request->login)
                ->orWhere('username', $request->login)
                ->first();

            if (
                !$user ||
                !Hash::check(
                    $request->password,
                    $user->password
                )
            ) {
                return $this->error(
                    'Credenciales incorrectas',
                    401
                );
            }

            // borrar tokens anteriores
            $user->tokens()->delete();

            $token = $user
                ->createToken('api-token')
                ->plainTextToken;

            return $this->success([
                'user' => $user,
                'token' => $token
            ]);
        } catch (\Throwable $e) {

            return $this->error(
                'Error login',
                500,
                $e->getMessage()
            );
        }
    }
}
