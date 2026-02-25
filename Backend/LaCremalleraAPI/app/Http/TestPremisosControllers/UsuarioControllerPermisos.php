<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Usuarios;
use Illuminate\Support\Facades\Hash;

// No tiene todos los metodos, este Controller es para hacer Testeos de permisos 

class UsuariosController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        if ($user->role !== 'admin') {
            return response()->json(['error' => 'No autorizado'], 403);
        }

        return Usuarios::all();
    }

    public function show($id, Request $request)
    {
        $auth = $request->user();

        if ($auth->role !== 'admin' && $auth->id != $id) {
            return response()->json(['error' => 'No autorizado'], 403);
        }

        return Usuarios::findOrFail($id);
    }

    public function store(Request $request)
    {
        $auth = $request->user();

        if ($auth->role !== 'admin' && $request->role !== 'cliente') {
            return response()->json([
                'error' => 'No puedes crear empleados o admins'
            ], 403);
        }

        $usuario = Usuarios::create([
            'nombre' => $request->nombre,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role
        ]);

        return response()->json($usuario, 201);
    }

    public function update($id, Request $request)
    {
        $usuario = Usuarios::findOrFail($id);
        $auth = $request->user();

        if ($auth->role !== 'admin' && $auth->id !== $usuario->id) {
            return response()->json(['error' => 'No autorizado'], 403);
        }

        if ($request->password) {
            $request->merge([
                'password' => Hash::make($request->password)
            ]);
        }

        $usuario->update($request->all());

        return $usuario;
    }

    public function destroy($id, Request $request)
    {
        $auth = $request->user();

        if ($auth->role !== 'admin') {
            return response()->json(['error' => 'Solo admin'], 403);
        }

        $usuario = Usuarios::findOrFail($id);
        $usuario->delete();

        return response()->json([
            'message' => 'Usuario eliminado'
        ]);
    }
}