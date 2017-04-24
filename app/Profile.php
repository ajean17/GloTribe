<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
  protected $fillable = [
      'user_id', 'slideOne','slideTwo','slideThree','slideFour','vision',
  ];

  public function user()//to find a user's profile
  {
    return $this->belongsTo(User::class);
  }
}
