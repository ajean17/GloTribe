@extends('layouts.master')

@section('title')
  Login | GloTribe
@endsection

@section('content')
    <h1>Sign in</h1>

    <form method="POST" action="/login">
      {{ csrf_field() }}
      <div class="form-group">
        <label for="name">User Name:</label>
        <input type="text" class="form-control" id="name" name="name">
      </div>

      <div class="form-group">
        <label for="password">Password:</label>
        <input type="password" class="form-control" id="password" name="password">
      </div>

      <div class="form-group">
        <button type="submit" class="btn btn-default">Login</button>
      </div>
      @include ('layouts.errors')
    </form>
    <a href="/forgotPassword">Forgot your username or password?</a>
@endsection
