<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserActivitionToken extends Model
{
    use HasFactory;

    protected $table = 'user_activition_tokens';

    protected $fillable = [
        'user_id', 'token'
    ];
}
