<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Dialogue extends Model
{
  protected $fillable = [
      'user1', 'user2', 'lastMessage',
  ];
}
