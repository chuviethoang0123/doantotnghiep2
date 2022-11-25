<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    
    public function responseSuccess($data = [], $message = 'success', $code = '00', $httpStatusCode = 200)
    {
        return response()->json([
            'status' => true,
            'message' => $message,
            'data' => $data,
            'code' => $code
        ], $httpStatusCode);
    }

    public function responseErrors($data = [], $message = '', $code = '00', $httpStatusCode = 200)
    {
        return response()->json([
            'status' => false,
            'message' => $message,
            'data' => $data,
            'code' => $code
        ], $httpStatusCode);
    }

    public function responseError($message = '', $data = null, $code = '00', $httpStatusCode = 200)
    {
        return response()->json([
            'status' => false,
            'message' => $message,
            'data' => $data,
            'code' => $code
        ], $httpStatusCode);
    }
}
