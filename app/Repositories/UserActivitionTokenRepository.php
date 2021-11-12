<?php

namespace App\Repositories;

use App\Models\StatusUpdate;
use App\Models\User;
use App\Models\UserActivitionToken;
use App\Repositories\Contracts\UserActivitionTokenRepositoryContract;

class UserActivitionTokenRepository implements UserActivitionTokenRepositoryContract
{

    //Create Token when register Account
    public function createNewToken(int $userId, $token)
    {
        try {
            $newToken = new UserActivitionToken();
            $newToken->user_id = $userId;
            $newToken->token = $token;
            $newToken->save();

            return $newToken;
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    //Check Token before active email
    public function checkToken($code)
    {
        try {
            $checkToken = UserActivitionToken::where(['token'=>$code])->first();

            return $checkToken;
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
}
