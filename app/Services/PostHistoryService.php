<?php

namespace App\Services;

use App\Models\Post;
use App\Models\PostHistory;
use App\Models\User;

class PostHistoryService
{
    public function historyOfMe()
    {
        $postListIds = Post::where('user_id', auth()->user()->id)->pluck('id')->all();
        if ($postListIds) {
            $listHistory = PostHistory::whereIn('post_id', $postListIds);
            return $listHistory;
        }
        return false;
    }

    public function historyOfFriend($id)
    {
        $user = User::where(['id' => $id])->first();
        $postFriend = Post::where('status', 1)->get();
        if (!$user) {
            return null;
        }
        if ((auth()->user()->isFriendWith($user) && $postFriend)) {
            $posts = Post::where(['user_id' => $id])->where('status', 1)->orWhere('status', 0);
            return $posts;
        }
        return null;
    }

    public function historyOfUser($id)
    {
        $posts = Post::where(['user_id' => $id])->Where('status', 0);
        return $posts;
    }
}
