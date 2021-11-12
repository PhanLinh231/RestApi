<?php

namespace App\Services;

use App\Repositories\Contracts\UserActivitionTokenRepositoryContract;
use App\Repositories\Contracts\UserRepositoryContract;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class UserActivitionTokenService
{
    protected $userActivitionTokenRepositoryContract;

    protected $userRepositoryContract;

    public function __construct(UserActivitionTokenRepositoryContract $userActivitionTokenRepositoryContract, UserRepositoryContract $userRepositoryContract)
    {
        $this->userActivitionTokenRepositoryContract = $userActivitionTokenRepositoryContract;

        $this->userRepositoryContract = $userRepositoryContract;
    }
    //Create Token when register Account
    public function createNewToken(int $userId)
    {
        $token = Str::random(16);

        return $this->userActivitionTokenRepositoryContract->createNewToken($userId, $token);
    }

    //Check Token && active email
    public function checkToken($code)
    {
        $checkToken = $this->userActivitionTokenRepositoryContract->checkToken($code);

        if($checkToken)
        {
            $userId = $checkToken->user_id;
            //Active Email
            $this->userRepositoryContract->activateUser($userId);
            $checkToken->delete();

            return true;
        }

        return false;

    }

}
