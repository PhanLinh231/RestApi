<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseController as BaseController;
use App\Http\Requests\RegisterRequest;
use App\Mail\ForgottenPasswordMail;
use App\Mail\RegisterUserMail;
use App\Services\PasswordRessetService;
use App\Services\UserActivitionTokenService;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class RegisterController extends BaseController
{
    protected $userService;
    protected $userActivitionTokenService;
    protected $passwordRessetService;

    public function __construct(
        UserService $userService,
        UserActivitionTokenService $userActivitionTokenService,
        PasswordRessetService $passwordRessetService
    ) {
        $this->userService = $userService;
        $this->userActivitionTokenService = $userActivitionTokenService;
        $this->passwordRessetService = $passwordRessetService;
    }
    /**
     * Register api
     *
     * @return \Illuminate\Http\Response
     */
    public function register(RegisterRequest $request)
    {
        //Register Accounnt
        $user = $this->userService->registerUser($request->all());

        if ($user) {
            //Create Token when register Account
            $token = $this->userActivitionTokenService->createNewToken($user->id);
            //Send Email to Activate
            Mail::to($user->email)->send(new RegisterUserMail($user, $token->token));

            $data['user'] = $user;

            return $this->sendResponse($data,"Check your mail to verified");
        }

        return $this->sendError([], 'No User Created', 404);

        //$success['token'] = $user->createToken('api_token')->accessToken;

    }

    //activate Email

    public function activateEmail($code)
    {
        $checkToken = $this->userActivitionTokenService->checkToken($code);

        $success['checkToken'] = $checkToken;

        return $this->sendResponse($success, "Account has been activated");
    }

    public function forgotPasswordCreate(Request $request)
    {
        $checkExistEmail = $this->userService->checkEmail($request->email);

        if (!$checkExistEmail) {
            return $this->sendError('fails.', ['error' => 'User Email does not exist'], 401);
        }
        //Create Token when request forgot password
        $passwordRessetData = $this->passwordRessetService->createPasswordResset($request->email);

        //Send Email forgot password
        Mail::to($request->email)->send(new ForgottenPasswordMail($passwordRessetData));

        $success['passwordResset'] = $passwordRessetData;
        return $this->sendResponse($success, "Request reset password sent");
    }

    //Reset Password
    public function forgotPasswordToken(Request $request, $token)
    {
        $checkReset = $this->passwordRessetService->checkReset($request->email, $token);

        if (!$checkReset) {
            return $this->sendError([], 'Detail dont match', 400);
        }

        $user = $this->userService->getUserByEmail($request->email);

        if (!$user) {
            return $this->sendError('User not fount', [], 400);
        }

        $user->password = bcrypt($request->password);
        $user->save();
        $data['user'] = $user;
        $checkReset->delete();

        return $this->sendResponse($data, "password reset successfully");
    }
}
