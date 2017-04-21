<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
  protected $fillable = ['user_id',/*'lat','long',*/'title','description','address','picture','eventDate','artGroup1','artGroup2'];

  public function user()//to find a user's profile
  {
    return $this->belongsTo(User::class);
  }
}
