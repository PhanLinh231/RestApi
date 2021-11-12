<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseController as BaseController;
use App\Models\Post;
use App\Models\PostHistory;
use App\Models\User;
use App\Services\PostHistoryService;
use Illuminate\Auth\Events\Failed;
use Illuminate\Http\Request;

class PostHistoryController extends BaseController
{
    protected $postHistoryService;

    public function __construct(PostHistoryService $postHistoryService)
    {
        $this->postHistoryService = $postHistoryService;
    }

    public function getListHistory()
    {

        if (!$this->postHistoryService->historyOfMe()) {
            return $this->sendError([], "No Post");
        }
        $listHistoryOfPost = $this->postHistoryService->historyOfMe()->get();

        $data['history of me'] = $listHistoryOfPost;

        return $this->sendResponse($data, "data");
    }

    public function getPostOfHistory($id)
    {
        $historyId = $this->postHistoryService->historyOfMe()->find($id);
        if ($historyId) {
            $postOfMe = Post::where('id', $historyId->id)->get();
            $data['post of history'] = $postOfMe;
            return $this->sendResponse($data, "data");
        }

        return $this->sendError([], "No Post");
    }

    public function getListHistoryFriend($id)
    {
        $user = User::where(['id' => $id])->first();
        if (!$this->postHistoryService->historyOfFriend($id)) {
            if($user)
            {
                $postIds = $this->postHistoryService->historyOfUser($id)->pluck('id')->all();
                $historyOfPost = PostHistory::whereIn('post_id', $postIds)->get();
                $data['list history of user'] = $historyOfPost;
                return $this->sendResponse($data, "data");
            }
            return $this->sendError([],"No Account");
        }
        $postIds = $this->postHistoryService->historyOfFriend($id)->pluck('id')->all();
        $historyOfPost = PostHistory::whereIn('post_id', $postIds)->get();
        $data['list history of friend'] = $historyOfPost;
        return $this->sendResponse($data, "data");
    }


    public function updateHistory(Request $request, $id)
    {
        if($this->postHistoryService->historyOfMe()->find($id))
        {
            $this->validate($request,[
                'title' => 'required|max:1024'
            ]);

            $this->postHistoryService->historyOfMe()->find($id)->update($request->all());
            $historyUpdate = $this->postHistoryService->historyOfMe()->find($id);
            $data['Update history'] = $historyUpdate;

            return $this->sendResponse($data,"data");
        }
        return $this->sendError([],"No History");
    }

    public function sortHistory(Request $request)
    {

        if($request->sortOrder && in_array($request->sortOrder,['asc','desc'])){
            $sortOrder = $request->sortOrder;
        }else {
            $sortOrder = 'desc';
        }
        $historyOfMe = $this->postHistoryService->historyOfMe()->orderBy('created_at',$sortOrder)->get();

        $data['sort history'] = $historyOfMe;

        return $this->sendResponse($data,"data");
    }

    public function sortPost(Request $request)
    {
        $postIds = Post::where('user_id',auth()->user()->id)->pluck('id');

        if($request->sortOrder && in_array($request->sortOrder,['asc','desc'])){
            $sortOrder = $request->sortOrder;
        }else {
            $sortOrder = 'desc';
        }

        $history = PostHistory::join('posts','post_histories.post_id','=','posts.id')->whereIn('post_id',$postIds)->orderBy('posts.end_date',$sortOrder)->get();

       $data['sort by Post'] = $history;

       return $this->sendResponse($data,"data");

    }
}
