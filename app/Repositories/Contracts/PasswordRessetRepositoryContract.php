<?php

namespace App\Repositories\Contracts;

interface PasswordRessetRepositoryContract
{
    public function createPasswordResset($email);

    public function checkReset($email,$token);

}
