<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostHistory extends Model
{
    use HasFactory;

    protected $table = 'post_histories';

    protected $fillable = [
        'post_id', 'title'
    ];

    public function post()
    {
        return $this->belongsTo(Post::class);
    }
    public function fileUpload()
    {
        return $this->hasMany(UserFiles::class,'post_id');
    }


    public function toArray()
    {
        return [
            'title' => $this->title,
            'post_id' => $this->post_id,
        ];
    }
}
