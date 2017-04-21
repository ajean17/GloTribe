@extends('layouts.master')

@section('title')
  Reclaim Account | OneTribe
@endsection

@section('content')
  <form method="GET" onsubmit="return false;" id="reclaimForm">
    {{ csrf_field() }}
    <h4>Enter your email address to reclaim your account</h4>

    <div class="form-group">
      <label for="email">Email:</label>
      <input type="email" class="form-control" id="forgotPasswordEmail" name="forgotPasswordEmail">
    </div>

    <div class="form-group">
      <button type="submit" id="emailButton" onclick="reclaim()" class="btn btn-default">Send</button>
    </div>
    <span id="status"></span>
  </form>
@endsection

@section('javascript')
  <script>
    function reclaim()
    {
      var email = document.getElementById('forgotPasswordEmail').value;
      var status = document.getElementById('status');
      if(email != "")
      {
        document.getElementById("emailButton").style.display = "none";
        status.innerHTML = 'please wait ...';
    		var ajax = ajaxObj("GET", "passwordSystem?email="+email);
    		ajax.onreadystatechange = function()
    		{
          if(ajaxReturn(ajax) == true)
    			{
            if(ajax.responseText != "email_success")
    				{
    					status.innerHTML = ajax.responseText;
    					document.getElementById("emailButton").style.display = "block";
    				}
    				else if(ajax.responseText == "email_success")
    				{
    					window.scrollTo(0,0);
    					document.getElementById("reclaimForm").innerHTML = "An email has been sent to "+email+" with instructions for reclaiming your account.";
    				}
          }
        }
        ajax.send();
      }
    }
  </script>
@endsection
