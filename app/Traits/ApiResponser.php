<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

trait ApiResponser
{
    
    protected function successResponse($data = null, int $code = 200): JsonResponse
    {
        return response()->json($data, $code);
    }

    protected function errorResponse(string $message, int $code): JsonResponse
    {
        $response = [ 
            'message' => $message
        ];

        return response()->json($response, $code);
    }

    
    protected function paginate(Collection $collection, int $perPage = 15): array
    {
        $response = [];
        $page     = LengthAwarePaginator::resolveCurrentPage();
        $results  = $collection->slice(($page - 1) * $perPage, $perPage)->values();
        
        $paginator = new LengthAwarePaginator(
            $results,
            $collection->count(),
        $perPage,
    $page,
            [
                'path' => LengthAwarePaginator::resolveCurrentPath(),
            ]
        );
        
        $paginator = $paginator->appends(request()->all());

        $response = [
            'data'         => $paginator->items() ?: [],
            'current_page' => $paginator->currentPage(),
            'last_page'    => $paginator->lastPage(),
            'per_page'     => $paginator->perPage(),
            'total'        => $paginator->total(),
            'links'        => [
                'first' => $paginator->url(1),
                'last'  => $paginator->url($paginator->lastPage()),
                'prev'  => $paginator->previousPageUrl(),
                'next'  => $paginator->nextPageUrl()
            ]
        ];

        return $response;
    }
}
