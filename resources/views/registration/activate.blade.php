<?php
  use App\User;
  if (isset($_GET['u']) && isset($_GET['e']) && isset($_GET['p']))
  {
  	// Connect to database and sanitize incoming $_GET variables
  	$u = preg_replace('#[^a-z0-9]#i', '', $_GET['u']);
  	$e = $_GET['e'];
  	$p = $_GET['p'];
  	// Evaluate the lengths of the incoming $_GET variable
  	if(strlen($u) < 3 || strlen($e) < 5 || strlen($p) == "")
    {
  		// Log this issue into a text file and email details to yourself
  		header("location: message?msg=activation_string_length_issues");
      exit();
  	}
  	// Check their credentials against the database
  	$numrows = User::where('name','=',$u)->where('email','=',$e)->where('password','=',$p)->where('activated','=','0')->first();
  	// Evaluate for a match in the system (0 = no match, 1 = match)
  	if($numrows == "")
    {
  		// Log this potential hack attempt to text file and email details to yourself
  		header("location: message?msg=Either your credentials are not matching anything in our system, or you have already been activated.");
      exit();
  	}
    else
    {
      // Match was found, you can activate them
      User::where('name','=',$u)->update(Array('activated'=> '1'));
    }

  	// Optional double check to see if activated in fact now = 1
  	$numrows = User::where('name','=',$u)->where('activated','=','1')->first();
  	// Evaluate the double check
    if($numrows == "")
    {
		// Log this issue of no switch of activation field to 1
      header("location: message?msg=activation_failure");
    	exit();
    }
    else if($numrows != "")
    {
		// Great everything went fine with activation!
      header("location: message?msg=activation_success");
    	exit();
    }
  }
  else
  {
  	// Log this issue of missing initial $_GET variables
  	header("location: message?msg=missing_GET_variables");
      exit();
  }
?>
