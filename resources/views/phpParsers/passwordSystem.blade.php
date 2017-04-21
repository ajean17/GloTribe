<?php
  use App\User;
  use App\Mail\Reset;

  if(isset($_GET['email']))
  {
    $e = $_GET['email'];
    $user = User::where('email','=',$e)->first();
    if($user == "")
    {
      echo "Invalid email address.";
    }
    else
    {
      //CONSIDER MAKING A TEMPORARY PASSWORD BRO!
      \Mail::to($user)->send(new Reset($user));
      echo "email_success";
    }
  }
  if(isset($_GET['u']) && isset($_GET['e']) && isset($_GET['p']))
  {
    $u = preg_replace('#[^a-z0-9]#i', '', $_GET['u']);
  	$e = $_GET['e'];
  	$p = $_GET['p'];
    $user = User::where('name','=', $u)->where('email','=', $e)->first();
    if($user == "")
    {
      echo "This account is invalid.  We apologize for the inconvenience and will resolve the issue promptly.";
    }
    else
    {
      $pass = bcrypt($p);
      User::where('name','=', $u)->where('email','=', $e)->update(Array('password' => $pass));
      $check = User::where('name','=', $u)->where('password','=',$pass)->first();
      if($check == "")
      {
        echo "Unable to reset your password. We have been notified and are resolving the issue.  We apoligize for the inconvenience";
      }
      else
      {
        echo "reset_success";
      }
    }
  }
?>
