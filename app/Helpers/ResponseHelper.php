<?php

namespace App\Helpers;

class ResponseHelper
{
    public static function jsonSuccess($data = null, $message = 'Success', $code = 200)
    {
        $response = [
            'success' => true,
            'message' => $message,
        ];

        if (!is_null($data)) {
            $response['data'] = $data;
        }

        return response()->json($response, $code);
    }

    public static function jsonError($message = 'Error', $code = 500)
    {
        return response()->json([
            'success' => false,
            'message' => $message,
        ], $code);
    }
}
