<?php

namespace App\Services;

use App\Repositories\Contracts\UserRepositoryContract;
use Illuminate\Support\Facades\Auth;

class UserService
{
    protected $userRepositoryContract;

    public function __construct(UserRepositoryContract $userRepositoryContract)
    {
        $this->userRepositoryContract = $userRepositoryContract;
    }

    //Register Accounnt
    public function registerUser(array $data)
    {
        $userData = [
            'name' => $data['name'],
            'email' => $data['email'],
            'password' =>bcrypt($data['password']),
        ];

        $newUser = $this->userRepositoryContract->registerUser($userData);

        return $newUser;
    }

    //Login Account
    public function loginUser(array $data)
    {
        if(Auth::attempt(['email' => $data['email'], 'password' => $data['password']]))
        {
            $user = Auth::user();
            return $user;
        }
    }

    public function checkUserActivate($email)
    {
        $checkIfExistEmail = $this->userRepositoryContract->checkIfExistEmail($email);

        if($checkIfExistEmail && $checkIfExistEmail->email_verified_at)
        {
            return true;
        }

        return false;
    }

    public function checkEmail($email)
    {
        $checkIfExistEmail = $this->userRepositoryContract->checkIfExistEmail($email);

        if($checkIfExistEmail){
            return true;
        }

        return false;
    }

    public function getUserByEmail($email)
    {
        return $this->userRepositoryContract->getUserByEmail($email);
    }
}
