<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name', 'email', 'password','activated',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    public function getroutekeyname()//returns the name of the user as it matches in the route
    {
      return 'name';
    }
    public function profile()//Pulls the messages sent by a User
    {
      return $this->hasOne(Profile::class);
    }
    public function posts()//Pulls the user's posts
    {
      return $this->hasMany(Post::class);
    }

    public function messages()//Pulls the messages sent by a User
    {
      return $this->hasMany(Message::class);
    }

    public function conversations()//Pulls the messages sent by a User
    {
      return $this->hasMany(Conversation::class);
    }

    public function reviews()
    {
      return $this->hasMany(Review::class);
    }

    public function patrons()//All of the member requests they made
    {
      return $this->hasMany(Patron::class);
    }

    public function friends()
    {
      return $this->hasMany(Friend::where('user1','=',$this->name)->where('accepted','=','1')
      ->orWhere('user2','=',$this->name)->where('accepted','=','1')->get());
    }
}
