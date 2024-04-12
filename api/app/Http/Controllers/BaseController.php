<?php
/**
 * Created by PhpStorm.
 * User: meysam.ghanbari
 * Date: 6/6/23
 * Time: 2:54 PM
 */

namespace App\Http\Controllers;


class BaseController extends Controller
{
    /**
     * Returns success response.
     *
     * @param mixed $result
     * @param string $message
     * @param int $status_code
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendResponse($result, string $message = '', $status_code = 200)
    {
        $response = [
            'status' => true,
            'data' => $result
        ];

        if ($message) {
            $response['message'] = $message;
        }

        return response()->json($response, $status_code);
    }

    /**
     * Returns error response.
     *
     * @param string $error
     * @param array $errorMessages
     * @param int $code
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendError(string $error, array $errorMessages = [], int $code = 404)
    {
        $response = [
            'status' => false,
            'message' => $error,
        ];

        if($errorMessages){
            $response['errors'] = $errorMessages;
        }

        return response()->json($response, $code);
    }
}
