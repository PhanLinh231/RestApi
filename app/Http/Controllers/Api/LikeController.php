<?php

namespace App\Http\Controllers\Api;

use App\Models\Like;
use App\Models\StatusUpdate;
use App\Http\Controllers\Api\BaseController as BaseController;
use Illuminate\Support\Facades\Auth;

class LikeController extends BaseController
{
    /**
     * Like a post.
     *
     * @param Post $post
     * @return \Illuminate\Http\RedirectResponse
     */
    public function likePost(StatusUpdate $statusUpdate)
    {
        if(Auth::user()){
            $like = Like::whereLikeableType('App\Models\StatusUpdate')
                            ->whereLikeableId($statusUpdate->id)
                            ->whereUserId(Auth::id())
                            ->first();

            $like ? $like->delete() : Like::create([
                'user_id' => Auth::id(),
                'likeable_id' => $statusUpdate->id,
                'likeable_type' => 'App\Models\StatusUpdate'
            ]);

            return $this->sendResponse([], 'Like Successfully');

        }

        return $this->sendError([],"Unauthorized",401);
    }
}
