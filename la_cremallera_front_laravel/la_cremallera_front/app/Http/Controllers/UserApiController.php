<?php

namespace App\Http\Controllers;

use App\Services\UserService;
use Illuminate\Http\Request;

class UserApiController extends Controller
{
    //asignar instancia de servicio api

    protected $apiService;

    public function __construct(UserService $service)
    {
        $this->apiService = $service;
    }

    //vista index
    public function index()
    {
        $usuariosRequest = $this->apiService->getUsers();

        if ($usuariosRequest["success"]) {
            $usuarios = $usuariosRequest["data"];
            return view('usuarios.index', compact('usuarios'));
        } else {
            //ha habido un error
            $errorResult = $usuariosRequest["data"];
            return view('error.index', compact('errorResult'));
        }
    }
}
