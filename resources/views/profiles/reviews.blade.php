@extends('layouts.master')

@section('title')
  {{$profileOwner->name}}'s Reviews | OneTribe
@endsection

<?php
  use App\Friend;
  use App\User;

  if($profileOwner->id == Auth::user()->id)
  {
    $isOwner = true;
    $reviewHead = "Your Reviews";
  }
  else
  {
    $isOwner = false;
    $reviewHead = "Reviews on ".$profileOwner->name;
  }
?>

@section('content')
  <h2>{{$reviewHead}}</h2>
@endsection
