<?php

namespace App\Repositories\Contracts;

interface UserActivitionTokenRepositoryContract
{
    //Create Token when register Account
    public function createNewToken(int $userId, $token);

    //Check Token before active email
    public function checkToken($code);
}
