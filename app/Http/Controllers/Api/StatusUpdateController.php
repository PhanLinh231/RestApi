<?php

namespace App\Http\Controllers\Api;


use App\Http\Controllers\Api\BaseController as BaseController;
use App\Http\Requests\PostRequest;
use App\Models\Post;
use App\Models\PostHistory;
use App\Models\Tag;
use App\Models\User;
use App\Models\UserFiles;
use App\Services\UploadFileService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StatusUpdateController extends BaseController
{

    protected $post;
    protected $uploadFileService;
    protected $userFiles;
    protected $tag;

    public function __construct(Post $post, UploadFileService $uploadFileService, UserFiles $userFiles,Tag $tag)
    {
        $this->post = $post;
        $this->uploadFileService = $uploadFileService;
        $this->userFiles = $userFiles;
        $this->tag = $tag;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $postPublic = Post::where('status',0)->where('user_id','!=',auth()->user()->id)->get();

        $userIds =Post::pluck('user_id')->all();
        $friendIds = auth()->user()->friends()->whereIn('id',$userIds)->pluck('id')->all();
        $postOfFriend = Post::where('status',1)->whereIn('user_id',$friendIds)->get();

        $postOfMe = Post::where('user_id',auth()->user()->id)->get();
        if ($postPublic || $postOfMe || $postOfFriend) {
            $data['posts'] = $postPublic;
            $data['post of Me'] = $postOfMe;
            $data['Post of Friend'] = $postOfFriend;
            return $this->sendResponse($data, "List Status");
        }
        return $this->sendError([], "No Status", 404);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PostRequest $request)
    {

        $validated = $request->validated();


        $postCreate =auth()->user()->posts()->create($validated);

        $success['post'] = $postCreate;
        $files = $request->file('fileName');
        if ($files) {
            foreach ($files as $file) {
                $dataUploadFile = $this->uploadFileService->uploadMultipleFile($file, 'status', $postCreate->id);
                UserFiles::create([
                    'post_id' => $postCreate->id,
                    'file_name' => $dataUploadFile['file_name'],
                    'link' => '/uploads/' . $dataUploadFile['link'],
                    'type' => $dataUploadFile['type']
                ]);
            }
        }

        if(!empty($request->tags)){
            foreach ($request->tags as $tag) {
                $tagInstance = $this->tag->firstOrCreate([
                    'keyword' => $tag
                ]);
                $tagIds[] = $tagInstance->id;
            }
        }

        $postCreate->tags()->attach($tagIds);

        $postHistory = $postCreate->postHistory()->create();
        $success['get History'] = $postHistory;
        return $this->sendResponse($success, 'Create Status successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $post = auth()->user()->posts()->find($id);
        if(empty($post))
        {
            return $this->sendError("No Post",[],404);
        }
        $success['post'] = $post;
        return $this->sendResponse($success, 'Show Post Detail');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(PostRequest $request, $id)
    {
        $validated = $request->validated();
        $post =  auth()->user()->posts()->find($id);
        $post->update($validated);


        $files = $request->file('fileName');
        if ($files) {
            foreach ($files as $file) {
                $dataUploadFile = $this->uploadFileService->uploadMultipleFile($file, 'status');

                if(!$this->userFiles->where('file_name',$dataUploadFile['file_name'])->first())
                {
                    $post->files()->create([
                        'file_name' => $dataUploadFile['file_name'],
                        'link' => '/uploads/' . $dataUploadFile['link'],
                        'type' => $dataUploadFile['type'],
                    ]);

                } else {
                    $this->userFiles->where('file_name',$dataUploadFile['file_name'])->delete();
                    $post->files()->create([
                        'file_name' => $dataUploadFile['file_name'],
                        'link' => '/uploads/' . $dataUploadFile['link'],
                        'type' => $dataUploadFile['type'],
                    ]);
                }
            }
        }
        if(!empty($request->tags)){
            foreach ($request->tags as $tag) {
                $tagInstance = $this->tag->firstOrCreate([
                    'keyword' => $tag
                ]);
                $tagIds[] = $tagInstance->id;
            }
            $post->tags()->sync($tagIds);
        }

        $success['post'] = $post;

        return $this->sendResponse($success, 'Update Status successfully');
    }

    public function getHiddenPost()
    {
        $postHidden = $this->post->onlyTrashed()->get();
        $data['list hidden'] = $postHidden;
        return $this->sendResponse($data,"Post");
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
        $this->post->onlyTrashed()->find($id)->forceDelete();
        $this->userFiles->onlyTrashed()->where('post_id', $id)->forceDelete();
        return $this->sendResponse([], "Delete successfully");
        $posDetail = $this->post->find($id);

    }

    public function restore($id)
    {
        $this->post->onlyTrashed()->find($id)->restore();
        $this->userFiles->onlyTrashed()->where('post_id', $id)->restore();
        return $this->sendResponse([], "Restore successfully");
    }

    public function restoreAll()
    {
        $this->post->onlyTrashed()->restore();
        $this->userFiles->onlyTrashed()->restore();
        return $this->sendResponse([], "Restore All successfully");
    }

    public function hidden($id)
    {
        if($posDetail)
        {
            $posDetail->delete();
            $this->userFiles->where('post_id', $id)->delete();
            return $this->sendResponse([], "Hidden Post successfully");
        } else {
            return $this->sendError([],"No Post");
        }
    }


}
