<?php

namespace App\Repositories\Contracts;

interface UserRepositoryContract
{
    //Register Account
    public function registerUser(array $data);

    //Active Email
    public function activateUser(int $userId);

    //Check Exist Email
    public function checkIfExistEmail($email);
    public function getUserByEmail($email);
}
