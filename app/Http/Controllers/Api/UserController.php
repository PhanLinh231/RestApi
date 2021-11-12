<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Http\Controllers\Api\BaseController as BaseController;
use Illuminate\Support\Facades\Auth;

class UserController extends BaseController
{

    public function getUser()
    {
        $users = User::orderBy('id', 'desc')->get();

        //$userStatus = User::with('statusUpdates')->find($user_id);
        $success['users'] = $users;
        return $this->sendResponse($success, 'List User');
    }

}
