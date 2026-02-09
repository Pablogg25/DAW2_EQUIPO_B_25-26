<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\ConnectionException;

class UserService
{
    protected $baseUrl;

    public function  __construct()
    {
        //insertar vairables de entorno

        $this->baseUrl = config('services.api.base_url');
    }

    // clase de consumo de api

    public function getUsers()
    {
        // $response=Http::withHeaders

        try {
            $response = Http::get($this->baseUrl . '/usuarios');

            if ($response->successful()) {
                //exito en petición

                return [
                    "data" => $response->json(),
                    "success" => true,
                    "status" => $response->status()
                ];
            }

            //no exitoso
            return [
                "data" => $response->json(),
                "success" => false,
                "status" => $response->status(),
            ];
        } catch (ConnectionException $e) {
            //excepción
            return [
                "data" => [
                    "code" => $e->getCode(),
                    "message" => $e->getMessage(),
                ],
                "success" => false,
            ];
        }
    }
}
