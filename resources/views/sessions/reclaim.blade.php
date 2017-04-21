<?php
  use App\User;

  if(isset($_GET['u']) && isset($_GET['e']))
  {
    $u = preg_replace('#[^a-z0-9]#i', '', $_GET['u']);
  	$e = $_GET['e'];
  }
?>
@extends('layouts.master')

@section('title')
  Change Password | OneTribe
@endsection

@section('content')
  <form method="GET" onsubmit="return false;" id="newPasswordForm">
    {{ csrf_field() }}
    <h4>Enter your email address to reclaim your account</h4>

    <!--div class="form-group">
      <label for="password"> Temporary Password:</label>
      <input type="password" class="form-control" id="temppassword" name="temppassword"
      onfocus="emptyElement('status')" maxlength="100">
    </div-->

    <div class="form-group">
      <label for="password"> New Password:</label>
      <input type="password" class="form-control" id="newPassword" name="newPassword"
      onfocus="emptyElement('status')" maxlength="100">
    </div>

    <div class="form-group">
      <label for="password_confirmation">Confirm Password:</label>
      <input type="password" class="form-control" id="newpassword_confirmation" name="newpassword_confirmation"
      onfocus="emptyElement('status')" maxlength="100">
    </div>

    <div class="form-group">
      <button type="submit" id="updatePasswordButton" onclick="updatePassword()" class="btn btn-default">Send</button>
    </div>
    <span id="status"></span>
  </form>
@endsection

@section('javascript')
  <script>
    function updatePassword()
    {
      var password = document.getElementById('newPassword').value;
      var confirm = document.getElementById('newpassword_confirmation').value;
      var status = document.getElementById('status');
      var u = "<?php echo $u?>";
      var e = "<?php echo $e?>";
      if(password != "" && confirm != "")
      {
        if(password != confirm)
        {
          status.innerHTML = 'Passwords do not match.';
        }
        else
        {
          document.getElementById("updatePasswordButton").style.display = "none";
          status.innerHTML = 'please wait ...';
      		var ajax = ajaxObj("GET", "passwordSystem?u="+u+"&e="+e+"&p="+password);
      		ajax.onreadystatechange = function()
      		{
            if(ajaxReturn(ajax) == true)
      			{
              if(ajax.responseText != "reset_success")
      				{
      					status.innerHTML = ajax.responseText;
      					document.getElementById("updatePasswordButton").style.display = "block";
      				}
      				else if(ajax.responseText == "reset_success")
      				{
      					window.scrollTo(0,0);
      					document.getElementById("newPasswordForm").innerHTML = "Your password has successfully been reset!";
      				}
            }
          }
          ajax.send();
        }
      }
    }
  </script>
@endsection
