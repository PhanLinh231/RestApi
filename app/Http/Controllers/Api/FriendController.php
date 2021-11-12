<?php

namespace App\Http\Controllers\Api;


use App\Http\Controllers\Api\BaseController as BaseController;
use App\Models\User;
use Illuminate\Http\Request;

class FriendController extends BaseController
{
    protected $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }
    public function getFriend()
    {

        $friendList = auth()->user()->friends();

        $request = auth()->user()->friendRequest();

        $blockFriend = auth()->user()->blockFriend();

        $data['Friend'] = $friendList;

        $data['Request Friend'] = $request;

        $data['Block Friend'] = $blockFriend;
        return $this->sendResponse($data, "Friend List,Friend Request and Block Friend");
    }


    public function getRecommendFriend()
    {
        $friendListId = auth()->user()->friends()->pluck('id')->all();
        $recommendFriend = User::all()->except($friendListId)->except(auth()->id());
        $data['Recommend Friend'] = $recommendFriend;
        return $this->sendResponse($data, "Friend List");
    }

    public function searchFriend(Request $request)
    {
        $friendList = auth()->user()->friends();
        $friendListId = $friendList->pluck('id');

        if ($request->keyword) {
               $friendSearch = User::select('name','email')->where('name', 'LIKE', '%' . $request->keyword . '%')->orWhere('email', 'LIKE', '%' . $request->keyword . '%')->whereIn('id', $friendListId);

               $friend = $friendSearch->get(['id', 'name', 'email']);
               $data['Friend'] = $friend;
               return $this->sendResponse($data, "Friend List");
        }

    }


    public function addFriend($id)
    {
        /*
			Lấy thông tin người dùng trong bảng user thông qua $id
		*/
        $user = User::where(['id' => $id])->first();

        if (!$user) {
            return $this->sendError([], "This account could not be found", 404);
        }

        if (auth()->user()->id === $user->id) {
            return $this->sendError([], "matches the user account", 403);
        }

        if (auth()->user()->hasFriendBlocked($user)) {

            return $this->sendResponse([], "User is blocked");
        }


        if (auth()->user()->hasFriendRequestPedding($user) || auth()->user()->hasFriendRequestPedding(Auth::user())) {
            return $this->sendResponse([], "You has sent a friend request");
        }

        if (auth()->user()->isFriendWith($user)) {
            return $this->sendResponse([], "You and this user are friends");
        }

        auth()->user()->addFriend($user);
        $data['user'] = $user->name;
        return $this->sendResponse($data, 'Added Friend');
    }

    public function acceptFriend($id)
    {

        $user = User::where(['id' => $id])->first();
        if (auth()->user()->hasFriendRequestReceived($user)) {
            if (!$user) {
                return $this->sendError([], "This account could not be found", 404);
            }
            if (auth()->user()->id === $user->id) {
                return $this->sendError([], "matches the user account", 403);
            }

            if (auth()->user()->hasFriendBlocked($user)) {

                return $this->sendResponse([], "User is blocked");
            }


            auth()->user()->acceptFriend($user);
            $data['user'] = $user->name;
            return $this->sendResponse($data, 'Accept Friend');
        }
        return $this->sendError([], "There are no request");
    }

    public function declineFriend($id)
    {
        $user = User::where(['id' => $id])->first();
        if (auth()->user()->hasFriendRequestReceived($user)) {
            if (!$user) {
                return $this->sendError([], "This account could not be found", 404);
            }
            auth()->user()->declineFriend($user);
            $data['user'] = $user->name;
            return $this->sendResponse($data, 'decline Friend');
        }
        return $this->sendError([], "Not Request");
    }

    public function blockFriend($id)
    {
        $user = User::where(['id' => $id])->first();

        if (!$user) {
            return $this->sendError([], "This account could not be found", 404);
        }

        if (auth()->user()->isFriendWith($user)) {
            auth()->user()->friends()->where('id', $user->id)->first()->pivot->update(['status' => -1]);
            $data['user'] = $user->name;
            return $this->sendResponse($data, 'block Friend');
        }

        auth()->user()->friendRequest()->where('id', $user->id)->first()->pivot->update(['status' => -1]);;
        $data['user'] = $user->name;
        return $this->sendResponse($data, 'block Friend');
    }

    public function unblockFriend($id)
    {
        $user = User::where(['id' => $id])->first();

        if (!$user) {
            return $this->sendError([], "This account could not be found", 404);
        }

        if (auth()->user()->blockFriend()) {
            auth()->user()->blockFriend()->where('id', $user->id)->first()->pivot->update(['status' => 2]);;
            $data['user'] = $user->name;
            return $this->sendResponse($data, 'Unblock Friend');
        }

        return $this->sendResponse([], "No account block");
    }

    public function deleteFriend($id)
    {
        $user = User::where(['id' => $id])->first();

        if (empty(auth()->user()->isFriendWith($user))) {
            return $this->sendError([], "User do not friend");
        }

        auth()->user()->deleteFriend($user);
        $data['user name'] = $user->name;
        return $this->sendResponse([], "UnFriend successfully");
    }
}
