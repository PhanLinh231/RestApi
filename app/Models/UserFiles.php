<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserFiles extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = 'user_files';

    protected $fillable = [
        'link', 'post_id','type','file_name'
    ];

    public function status()
    {
       return $this->belongsTo(StatusUpdate::class);
    }

    public function toArray()
    {
        return [
            'link' => $this->link,
            'type'=>$this->type
        ];
    }
}
