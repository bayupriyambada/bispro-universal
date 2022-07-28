<?php

namespace App\Helpers;

class ResponseHelpers
{
    public static function ResponseSuccess($status, $message, $data)
    {
        return response()->json([
            'status' => $status,
            'message' => $message,
            'data' => $data,
        ]);
    }
    public static function ResponseSuccessFilter($status, $message, $data)
    {
        return response()->json([
            'status' => $status,
            'message' => $message ?? '',
            'data' => $data,
        ]);
    }

    public static function ResponseError($message, $status = 400)
    {
        return response()->json(
            [
                'status' => $status,
                'message' => $message,
            ],
            $status
        );
    }
}
