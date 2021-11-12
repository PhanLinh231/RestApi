<?php

namespace App\Http\Controllers\Api;

use Laravel\Socialite\Facades\Socialite;
use App\Http\Controllers\Api\BaseController as BaseController;
use App\Models\SocialAccount;
use App\Models\User;

class SocialController extends BaseController
{
    public function redirect(string $provider)
    {
        $url = Socialite::with($provider)->stateless()->redirect()->getTargetUrl();

        $success['url'] = $url;

        return $this->sendResponse($success, 'Login with social link');
    }

    public function handleCallback(string $provider)
    {
        $socialUser = Socialite::driver($provider)->stateless()->user();

        $userSocialAccount = SocialAccount::where([
            'name'=> $provider,
            'id' => $socialUser->getId()
        ])->first();

        if($userSocialAccount) {
            $success['token'] = $socialUser->token;
            $success['user'] = $userSocialAccount->user;
            return $this->sendResponse($success, 'Login successfully');
        }

        $user = User::join('user_channel','user_channel.user_id','=','users.id')->get();

        if(empty($user))
        {
            $socialCreate = SocialAccount::create([
                'name' => $provider,
                'id' => $socialUser->id,

            ]);
            $userSocialAccount->user_channel()->attach(auth()->id(),[
                'accsess_token'=>$socialUser->token,
                'refresh_token'=>'',
            ]);

        }

        $success['token'] =$user->createToken('token')->accessToken;
        $success['user'] = $user;

        return $this->sendResponse($success, 'Login Successfully');

        return $user;

    }
}
