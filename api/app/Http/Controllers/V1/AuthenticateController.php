<?php
/**
 * Created by PhpStorm.
 * User: meysam.ghanbari
 * Date: 6/6/23
 * Time: 7:37 PM
 */

namespace App\Http\Controllers\V1;

use App\Http\Requests\Auth\LoginRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\BaseController;

class AuthenticateController extends BaseController
{
    /**
     * @param LoginRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function login(LoginRequest $request)
    {
        $request->authenticate();

        /** @var User $user */
        $user = Auth::user();
        $response['user'] = new UserResource($user);
        $response['token'] = $user->createToken("{$user->username}App")->plainTextToken;

        return $this->sendResponse($response);
    }

    /**
     * Destroy an authenticated session.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function destroy(Request $request): Response
    {
        $request->user()->currentAccessToken()?->delete();

        return response('', 204);
    }
}
