@extends('layouts.master')

@section('title')
  {{$profileOwner->name}}'s Postings | OneTribe
@endsection

<?php
  use App\Friend;
  use App\User;
  use App\Post;
  
  if($profileOwner->id == Auth::user()->id)
  {
    $isOwner = true;
    $postHead = "Your Postings";
  }
  else
  {
    $isOwner = false;
    $postHead = "Postings by ".$profileOwner->name;
  }
  $posts = Post::where('user_id','=',$profileOwner->id)->get();
  //echo "Posts: ".$posts;

?>

@section('content')
  <h2>{{$postHead}}</h2>
  <div id="postingsList">
    @foreach($posts as $post)
      <div class='profilePost'><b>{{$post->title}}</b></div><br/>
    @endforeach
  </div>
@endsection
