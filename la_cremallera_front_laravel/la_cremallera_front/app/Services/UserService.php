<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class UserService{
    protected $baseUrl;

    public function  __construct() {
        //insertar vairables de entorno

        $this->baseUrl=config('services.api.base_url');
    }

    // clase de consumo de api

    public function getUsers(){
        // $response=Http::withHeaders
        $response= Http::get($this->baseUrl.'/usuarios');

        // return $response->json();
        return $response->json();
    }
}