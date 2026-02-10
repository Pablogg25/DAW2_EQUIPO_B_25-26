<?php

namespace App\Http\Controllers;

abstract class Controller
{
    //
}




// namespace App\Http\Controllers;

// use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
// use Illuminate\Foundation\Bus\DispatchesJobs;
// use Illuminate\Foundation\Validation\ValidatesRequests;
// use Illuminate\Routing\Controller as BaseController;
// use Illuminate\Http\JsonResponse;

// class Controller extends BaseController
// {
//     use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

//     /**
//      * Respuesta JSON de éxito
//      *
//      * @param mixed $data
//      * @param string $message
//      * @param int $status
//      * @return JsonResponse
//      */
//     protected function respondWithSuccess($data = null, string $message = 'Operación exitosa', int $status = 200): JsonResponse
//     {
//         return response()->json([
//             'success' => true,
//             'message' => $message,
//             'data' => $data,
//         ], $status);
//     }

//     /**
//      * Respuesta JSON de error
//      *
//      * @param string $message
//      * @param int $status
//      * @param mixed $errors
//      * @return JsonResponse
//      */
//     protected function respondWithError(string $message = 'Ocurrió un error', int $status = 400, $errors = null): JsonResponse
//     {
//         return response()->json([
//             'success' => false,
//             'message' => $message,
//             'errors' => $errors,
//         ], $status);
//     }

//     /**
//      * Respuesta JSON para colección paginada
//      *
//      * @param \Illuminate\Contracts\Pagination\Paginator $paginator
//      * @param string $message
//      * @return JsonResponse
//      */
//     protected function respondWithPagination($paginator, string $message = 'Operación exitosa'): JsonResponse
//     {
//         return response()->json([
//             'success' => true,
//             'message' => $message,
//             'data' => $paginator->items(),
//             'pagination' => [
//                 'total' => $paginator->total(),
//                 'per_page' => $paginator->perPage(),
//                 'current_page' => $paginator->currentPage(),
//                 'last_page' => $paginator->lastPage(),
//             ]
//         ]);
//     }
// }

// BaseController → Clase base real de Laravel para controladores.

// AuthorizesRequests → Permite usar $this->authorize(...) para políticas de autorización.

// DispatchesJobs → Permite usar $this->dispatch(...) para jobs en Laravel.

// ValidatesRequests → Permite usar $this->validate(...) para validar formularios y requests.

// composer dump-autoload

