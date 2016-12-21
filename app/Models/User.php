<?php

namespace Chatty\Models;

use Chatty\Models\Status;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    protected $table = 'users';


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username',
        'email', 
        'password',
        'first_name',
        'last_name',
        'location',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 
        'remember_token',
    ];

    public function getName()
    {
        if($this->first_name && $this->last_name){
            return "{$this->first_name} {$this->last_name}";
        }

        elseif ($this->first_name){
            return $this->first_name;
        } else

        {
             return null;
        } 
    }

    
    public function getNameOrUsername()
    {
        return $this->getName() ?: $this->username;
    }


    public function getFirstNameOrUsername()
    {
        return $this->first_name ?: $this->username;
    }

    public function getAvatarUrl()
    {
        return "https://www.gravatar.com/avatar/{{md5($this->email)}}?d=mm&s=40";//mm means mystery map
    }


    public function statuses()
    {   //THIS RELATIONSHIP MEANS A USER CAN HAVE MANY STATUSES
        return $this->hasMany('Chatty\Models\Status', 'user_id');//the user has many status with user_id of satus table directly relating to the id of the users table with fillable 'body'
    }

    public function likes()
    {
        return $this->hasMany('Chatty\Models\Like', 'user_id');
    }

    public function friendsOfMine()
    {   
        //The below means that i am defining the relation between the user table and the friends table
        //on the key user_id, and then joining it to the key friend_id

        return $this->beLongsToMany('Chatty\Models\User', 'friends', 'user_id', 'friend_id');
        //Chatty\Models\User => Name of related model
        //'friends' => Name of joining table
        // 'user_id' => Foreign key name of the model on which you are the defining the relationship.
        //friend_id => Foreign key name of the model that you are joining to
    }


    public function friendsOf()
    {
        return $this->beLongsToMany('Chatty\Models\User', 'friends', 'friend_id', 'user_id');
    }

    public function friends()
    {
        return $this->friendsOfMine()->wherePivot('accepted', true)->get()
        ->merge($this->friendsOf()->wherePivot('accepted', true)->get());
    }


    public function friendRequests()
    {
        return $this->friendsOfmine()->wherePivot('accepted', false)->get();
    }


    // FRIENDS MODEL

     public function friendRequestsPending()
    {
        return $this->friendsOf()->wherePivot('accepted', false)->get();
    }

    public function hasFriendRequestsPending(User $user)
    {
        return (bool) $this->friendRequestsPending()->where('id', $user->id)->count();
    }


    public function hasFriendRequestsRecieved(User $user)
    {
        return (bool) $this->friendRequests()->where('id', $user->id)->count();
    }


    public function addFriend(User $user)
    {
        $this->friendsOf()->attach($user->id);
    }

    public function deleteFriend(User $user)
    {
        $this->friendsOf()->detach($user->id);
        $this->friendsOfMine()->detach($user->id);
    }


    public function acceptFriendRequest(User $user)
    {
        $this->friendRequests()->where('id', $user->id)->first()->pivot->
        update([
            'accepted' => true,
        ]);
        //pivot refers to the original table
    }


    public function isFriendWith(User $user)
    {
        return (bool) $this->friends()->where('id', $user->id)->count();
    }

    public function hasLikedStatus(Status $status)
    {
        //$this->id represent the current user
        return (bool) $status->likes->where('user_id', $this->id)->count();
        
    }

}
