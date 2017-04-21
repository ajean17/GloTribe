<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Patron extends Model
{
  protected $fillable = ['post_id','user_id',];

  protected $hidden = ['accepted',];

  public function user()//to find a user's profile
  {
    return $this->belongsTo(User::class);
  }
  public function post()//to find a user's profile
  {
    return $this->belongsTo(Post::class);
  }
}
