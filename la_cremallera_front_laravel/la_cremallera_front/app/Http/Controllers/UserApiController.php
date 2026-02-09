<?php

namespace App\Http\Controllers;

use App\Services\UserService;
use Illuminate\Http\Request;

class UserApiController extends Controller
{
    //asignar instancia de servicio api

    protected $apiService;

    public function __construct(UserService $service) {
        $this->apiService = $service;
    }

    //vista index
    public function index(){

        $usuarios=$this->apiService->getUsers();

        return view('usuarios.index',compact('usuarios'));

    }


}
