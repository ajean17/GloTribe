@extends('layouts.master')

@section('title')
  Registration | OneTribe
@endsection

@section('content')
  <h1>Sign Up</h1>

  <form method="GET" id="signupform" onsubmit="return false;">
    {{csrf_field()}}

    <div class="form-group">
      <label for="name">User Name:</label>
      <input type="text" class="form-control" id="username" name="name"
      maxlength="16">
      <span id="unamestatus"></span>
    </div>

    <div class="form-group">
      <label for="email">Email:</label>
      <input type="email" class="form-control" id="email" name="email"
      onkeyup="restrict('email')" maxlength="88">
    </div>

    <div class="form-group">
      <label for="password">Password:</label>
      <input type="password" class="form-control" id="password" name="password"
      maxlength="100">
    </div>

    <div class="form-group">
      <label for="password_confirmation">Confirm Password:</label>
      <input type="password" class="form-control" id="password_confirmation" name="password_confirmation"
      maxlength="100">
    </div>

    <div class="form-group">
      <button type="submit" id="signupbtn" class="btn btn-default">
        Create Account
      </button>
      <span id="status"></span>
    </div>
  </form>
  <hr/>
  <div>
    <a href="#" id= "termsLink" onclick="return false">
      View the Terms Of Use
    </a>
  </div>
  <div id="terms" style="display:none;">
      <h3>OneTribe Terms Of Use</h3>
      <p>1. Play nice here.</p>
      <p>2. Take a bath before you visit.</p>
      <p>3. Brush your teeth before bed.</p>
  </div>
  @include('layouts.errors')
@endsection

@section('javascript')
  <script>
    var token = '{{Session::token()}}';
    var url = '{{route('register')}}';
    $(document).ready(function()
    {
      var $user = $('#username');
      var $email = $('#email');
      var $pass = $('#password');
      var $passConf = $('#password_confirmation');
      var $status = $('#status');

      $user.on('click', function(){$status.html("");});
      $email.on('click', function(){$status.html("");});
      $pass.on('click', function(){$status.html("");});
      $passConf.on('click', function(){$status.html("");});
      $('#termsLink').on('click', function()
      {
        //console.log("show terms");
        $('#terms').css('display','block');
        $status.html("");
      })
      //checks user name
      $user.on('blur', function()
      {
        if($user.val() != "")
        {
          $('#unamestatus').html('checking...');
          //console.log($user.val());
          $.ajax(
          {
            method: 'POST',
            url: url,
            data: {username: $user.val(), _token: token}
          }).done(function (msg)
          {
            //console.log(msg['message']);
            $('#unamestatus').html(msg['message']);
          });
        }
      });

      $('#signupbtn').on('click', function()
      {
        //console.log("sign up");
        var u = $user.val();
        var e = $email.val();
        var p1 = $pass.val();
        var p2 = $passConf.val();

        if(u == "" || e == "" || p1 == "" || p2 == "")//if any of the above elements are empty
        {
          $status.html('Fill out all of the form data');
        }
        else if(p1 != p2)//if the passwords do not match
        {
          $status.html('Your password fields do not match');
        }
        else if( $('#terms').css('display') == "none")//if the terms have not been views yet, display is still none
        {
          $status.html('Please view the terms of use');
        }
        else
        {
          $('#signupbtn').css('display','none');
          $status.html("please wait...");

          $.ajax(
            {
              method: 'POST',
              url: url,
              data: {user: u,email: e, password: p1, _token: token}
            }).done(function (msg)
            {
              //console.log(msg['message']);
              if(msg['message'] != "signup_success")
              {
                $('#signupbtn').css('display','block');
                $status.html(msg['message']);
              }
              else if(msg['message'] == "signup_success")
              {
                $('#signupform').html("OK "+u+", check your email inbox and junk mail box at <u>"+e+"</u> in a moment to complete the sign up process by activating your account. You will not be able to do anything on the site until you successfully activate your account.")
                $('#terms').css('display','none');
                $('#termsLink').css('display','none');
              }
            });
        }
      });
    });
  </script>
@endsection
