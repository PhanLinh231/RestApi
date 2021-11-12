<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function likes()
    {
        return $this->morphToMany(User::class,'likeable');
    }

    public function isLiked()
    {
        $like = $this->likes()->whereUserId(Auth::id())->first();

        return isset($like) ? true : false;
    }

    public function files()
    {
        return $this->hasMany(UserFiles::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class,'post_tags','post_id','tag_id');
    }

    public function postHistory()
    {
        return $this->hasOne(PostHistory::class,'post_id');
    }

    public function toArray()
    {
        return [
            'id' =>$this->id,
            'user_id'=>auth()->user()->id,
            'content' => $this->content,
            'country' => $this->country,
            'city' => $this->city,
            'village' => $this->village,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'file upload' => $this->files()->get(),
            'tag' => $this->tags()->get(),
        ];
    }
}
