<?php

namespace App\Repositories;

use App\Models\PasswordReset;
use Illuminate\Support\Str;

use App\Repositories\Contracts\PasswordRessetRepositoryContract;

class PasswordRessetRepository implements PasswordRessetRepositoryContract
{
   public function createPasswordResset($email)
   {
        try {

            $newReset = PasswordReset::updateOrCreate(
                ['email'=>$email],
                [
                    'email' => $email,
                    'token' => Str::random(16),
                ]);

            return $newReset;
        } catch (\Exception $e) {
            return $e->getMessage();
        }
   }

   public function checkReset($email, $token)
   {
        return PasswordReset::where([
            'email'=>$email,
            'token'=> $token
        ])->first();
   }
}
