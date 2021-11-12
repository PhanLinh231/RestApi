<?php

namespace App\Services;

use App\Repositories\Contracts\PasswordRessetRepositoryContract;
use Illuminate\Support\Facades\Auth;

class PasswordRessetService
{
    protected $passwordRessetRepositoryContract;

    public function __construct(PasswordRessetRepositoryContract $passwordRessetRepositoryContract)
    {
        $this->passwordRessetRepositoryContract = $passwordRessetRepositoryContract;
    }

    public function createPasswordResset($email)
    {
        $newResset = $this->passwordRessetRepositoryContract->createPasswordResset($email);

        return $newResset;
    }

    public function checkReset($email,$token)
    {
        return $this->passwordRessetRepositoryContract->checkReset($email,$token);
    }

}
