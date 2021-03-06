<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
    
    public function userdetails()
    {
        return $this->hasOne(Userdetail::class);
    }
    
    public function contents()
    {
        return $this->hasMany(Content::class);
    }
    
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
    
    public function followings()
    {
        return $this->belongsToMany(User::class,'user_follow','user_id','follow_id')->withTimestamps();
    }
    
    public function followers()
    {
        return $this->belongsToMany(User::class,'user_follow','follow_id','user_id')->withTimestamps();
    }
    
    public function follow($userId)
    {
        $exist = $this->is_following($userId);
        $its_me = $this->id == $userId;
        
        if($exist || $its_me){
            return false;
        }else{
            $this->followings()->attach($userId);
            return true;
        }
    }
    
    public function unfollow($userId)
    {
        $exist = $this->is_following($userId);
        $its_me = $this->id == $userId;
        
        if($exist && !$its_me){
            $this->followings()->detach($userId);
            return true;
        }else{
            return false;
        }
    }
    
    public function is_following($userId)
    {
        return $this->followings()->where('follow_id',$userId)->exists();
    }
    
    public function feed_contents()
    {
        $follow_user_ids = $this->followings()->pluck('users.id')->toArray();
        $follow_user_ids[] = $this->id;
        return Content::whereIn('user_id',$follow_user_ids);
    }
    
    public function favorites()
    {
        return $this->belongsToMany(Content::class,'content_favorite','user_id','content_id')->withTimestamps();
    }
    
    public function favorite($contentId)
    {
        $exist = $this->is_favorite($contentId);
        
        if($exist){
            return false;
        }else{
            $this->favorites()->attach($contentId);
            return true;
        }
    }
    
    public function unfavorite($contentId)
    {
        $exist = $this->is_favorite($contentId);
        
        if($exist){
            $this->favorites()->detach($contentId);
            return true;
        }else{
            return false;
        }
    }
    
    public function is_favorite($contentId)
    {
        return $this->favorites()->where('content_id',$contentId)->exists();
    }
}