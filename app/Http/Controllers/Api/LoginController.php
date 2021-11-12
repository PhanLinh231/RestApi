<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Api\BaseController as BaseController;
use App\Services\UserService;

class LoginController extends BaseController
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function login(Request $request)
    {
        //Check User Active or Not
        $checkUserActive = $this->userService->checkUserActivate($request->email);

        if ( !$checkUserActive )
        {
            return $this->sendError('fails.', ['error' => 'User must be activate']);
        }

        //User is activated
        $newUser = $this->userService->loginUser($request->all());

        if($newUser)
        {
            $data['token'] = $newUser->createToken('api-token')->accessToken;
            //$data['user'] = $newUser;

            return $this->sendResponse($data, 'User login successfully.');
        }

        return $this->sendError('fails.', ['error' => 'Check your email or password']);
    }

    public function logout(Request $request)
    {
        $request->user()->token()->delete();
        return $this->sendResponse([],"User log out");
    }
}
