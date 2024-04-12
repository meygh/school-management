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
    
        $user = Auth::user();
    
        
        $response['user'] = new UserResource($user);
        $response['token'] = $user->createToken("{$user->name}App")->plainTextToken;
    
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
        Auth::guard('web')->logout();
        $request->user()->currentAccessToken()?->delete();
        
//        $request->session()->invalidate();
//        $request->session()->regenerateToken();
        
        return response('', 204);
    }
}