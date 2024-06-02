<?php
namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait ApiResponse{

    /**
     * @param array $data
     * @param array $messages
     * @param int $status
     * @return JsonResponse
     */
    public function response(mixed $data, array $messages = [], int $status = 200): JsonResponse
    {
        $formattedData = [
            'data' => $data,
            'messages'=> $messages,
            'success' => true,
        ];

        return response()->json($formattedData)->setStatusCode($status);
    }

    /**
     * @param array $messages
     * @param int $status
     * @return JsonResponse
     */
    public function error(array $messages, int $status = 500): JsonResponse
    {
        $formattedData = [
            'data' => [],
            'messages'=> $messages,
            'success' => false,
        ];

        return response()->json($formattedData)->setStatusCode($status);
    }

    /**
     * @param array $messages
     * @param int $status
     * @return JsonResponse
     */
    public function ok(array $messages = [], int $status = 200): JsonResponse
    {
        $formattedData = [
            'data' => [],
            'messages'=> $messages,
            'success' => true,
        ];

        return response()->json($formattedData)->setStatusCode($status);
    }
}
