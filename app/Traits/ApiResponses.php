<?php

namespace App\Traits;

trait ApiResponses
{
    protected function ok($message, $data = [])
    {
        return $this->success($message, $data, 200);
    }
    protected function success($message = null, $data = [], $statusCode = 200)
    {
        return response()->json([
            'message' => $message,
            'status' => $statusCode,
            'data' => $data
        ], $statusCode);
    }

    protected function error($message = null, $statusCode)
    {
        return response()->json([
            'message' => $message,
            'status' => $statusCode,
        ], $statusCode);
    }
}
