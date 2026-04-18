<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BaseApiController extends Controller
{
    /**
     * success response method.
     *
     * @param $result
     * @param $message
     *
     * @return \Illuminate\Http\Response
     */
    public function sendResponse($result, $message = "")
    {
        $response = [
            'status' => true,
            'data'    => $result,
        ];
        if ($message) {
            $response['message'] = $message;
        }

        return response()->json($response, 200);
    }

    /**
     * return error response.
     *
     * @param $error
     * @param  array  $errorMessages
     * @param  int  $code
     *
     * @return \Illuminate\Http\Response
     */
    public function sendError($error, $errorMessages = [], $code = 404)
    {
        $response = [
            'status' => false,
            'message' => $error,
        ];

        if (!empty($errorMessages)) {
            $response['data'] = $errorMessages;
        }

        return response()->json($response, $code);
    }


    /**
     * return Unauthorized response.
     *
     * @param $error
     * @param  int  $code
     *
     * @return \Illuminate\Http\Response
     */
    public function unauthorizedResponse($error = 'Forbidden', $code = 403)
    {
        $response = [
            'status' => false,
            'message' => $error,
        ];

        return response()->json($response, $code);
    }
}