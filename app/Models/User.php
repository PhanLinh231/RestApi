<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function socialAccounts()
    {
        return $this->hasMany(SocialAccount::class);
    }

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    /**
     * Get the liked posts.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function likedStatuses()
    {
        return $this->morphedByMany(Post::class, 'likeable');
    }

    public function toArray()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,

        ];
    }
    //Friend
    public function friendOfMine()
    {
        return $this->belongsToMany(User::class, 'friend_users', 'user_id', 'friend_id');
    }

    public function friendOf()
    {
        return $this->belongsToMany(User::class, 'friend_users', 'friend_id', 'user_id');
    }


    public function friends()
    {
        return $this->friendOfMine()->wherePivot('status', 2)->get()->merge($this->friendOf()->wherePivot('status', 2)->get());
    }

    public function friendRequest()
    {
        return $this->friendOf()->wherePivot('status', 0)->get();
    }

    public function blockFriend()
    {
        return $this->friendOf()->wherePivot('status', -1)->get();

    }
    public function friendDecline()
    {
        return $this->friendOf()->wherePivot('status',1)->get();
    }

    public function friendRequestPedding()
    {
        return $this->friendOfMine()->wherePivot('status', 0)->get();
    }

    public function hasFriendRequestPedding(User $user)
    {
        return (bool) $this->friendRequestPedding()->where('id', $user->id)->count();
    }

    public function hasFriendRequestReceived(User $user)
    {
        return (bool) $this->friendRequest()->where('id', $user->id)->count();
    }

    public function hasFriendBlocked(User $user)
    {
        return (bool) $this->blockFriend()->where('id', $user->id)->count();
    }

    public function addFriend(User $user)
    {
        $this->friendOfMine()->attach($user->id);
    }

    public function deleteFriend(User $user)
    {
        $this->friendOf()->detach($user->id);
        $this->friendOfMine()->detach($user->id);
    }

    public function acceptFriend(User $user)
    {
        $this->friendRequest()->where('id', $user->id)->first()->pivot->update(['status' => 2]);
    }

    public function declineFriend(User $user)
    {
        $this->friendRequest()->where('id', $user->id)->first()->pivot->update(['status' => 1]);
    }

    public function undecline(User $user)
    {
        $this->friendDecline()->where('id', $user->id)->first()->pivot->update(['status' => 2]);
    }

    public function isFriendWith(User $user)
    {
        return (bool) $this->friends()->where('id', $user->id)->count();
    }
}
