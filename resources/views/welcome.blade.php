<!DOCTYPE html>
  <html>
    <head>
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
      <meta name="description" content="">
      <meta name="author" content=""></meta>
      <link rel="icon" href="../../favicon.ico">
      <title>
        Welcome to GloTribe
      </title>
      @include('layouts.style')
      <style>
        body
        {
          background-image: url("/images/Splash.jpg");
          background-size: cover;
          background-repeat: no-repeat;
          color:white;
        }
      </style>
    </head>
    <body>
      @include('layouts.header')
      <div class="container">
        <br/>
        <h1><b>Welcome to GloTribe</b></h1>
        <h3>where big ideas become real</h3>
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
      </div>
    </body>
  </html>
