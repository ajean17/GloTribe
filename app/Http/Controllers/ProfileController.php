<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Post;
class ProfileController extends Controller
{
  public function __construct()
  {
      $this->middleware('auth');
  }

  public function show(User $profileOwner)
  {
    return view('profiles.show',compact('profileOwner'));
  }

  public function helpShow(Post $id)
  {
    return redirect("/profile"."/".$id->user->name."");
  }

  public function search()
  {
    return view('profiles.search');
  }

  public function postings(User $profileOwner)
  {
    return view('profiles.postings',compact('profileOwner'));
  }

  public function network(User $profileOwner)
  {
    return view('profiles.network',compact('profileOwner'));
  }

  public function reviews(User $profileOwner)
  {
    return view('profiles.reviews',compact('profileOwner'));
  }

}
