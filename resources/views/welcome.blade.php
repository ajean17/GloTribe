@extends('layouts.master')

@section('title')
  Welcome to OneTribe
@endsection

@section('content')
  <h1>One Tribe</h1>
  @if (!Auth::check())
  <div class="row">
    <div class="col-3 loginBox">
      <h5>Login</h5>
      <form method="POST" action="/login">
        {{ csrf_field() }}
        <div class="">
          <label for="name">User Name:</label>
          <input type="text" class="form-control" id="name" name="name">
        </div>

        <div class="">
          <label for="password">Password:</label>
          <input type="password" class="form-control" id="password" name="password">
        </div>

        <div class="">
          <button type="submit" class="">Login</button>
        </div>
        @include ('layouts.errors')
      </form>
      <a href="/forgotPassword">Forgot your username or password?</a>
    </div>
  </div>
  @endif
  <div class="row">
    <div class="col-12 aboutLeft">
      <img style="text-align:left" src="" width="250px" height="250px" alt="General">
      <p>General description of FindEm</p>
    </div>
  </div>
  <div class="row">
    <div class="col-12 aboutRight">
      <img src="" width="250px" height="250px" alt="Feature One">
      <p>Description of map and posting system</p>
    </div>
  </div>
  <div class="row">
    <div class="col-12 aboutLeft">
      <img src="" width="250px" height="250px" alt="Feature Two">
      <p>Description of Portfolio System</p>
    </div>
  </div>
  <div class="row">
    <div class="col-12 aboutRight">
      <img src="" width="250px" height="250px" alt="Feature Two">
      <p>Description of trustworthy review system</p>
    </div>
  </div>
@endsection
