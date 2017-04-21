<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{

  protected $fillable = [
      'user1', 'user2', 'message',
  ];

  public function user()//to find a user's profile
  {
    return $this->belongsTo(User::class);
  }
}
