<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BaseController extends Controller
{
    public function successResponse($result, $message)
    {
        $response = [
            'success' => true,
            'message' => $message,
            'data'    => $result,
        ];

        return response()->json($response, 200);
    }

    public function sendError($error, $errorMessages = [], $code = 404)
    {
        $response = [
            'success'  => false,
            'message'  => $error,
        ];

        if (!empty($errorMessage)) {
            $response['data'] = $errorMessages;
        }

        return response()->json($response, $code);
    }
}
