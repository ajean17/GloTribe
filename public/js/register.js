//Sign UP aka Registration JavaScript
function restrict(elem) //Will restrict the characters entered into the Username and Email fields (Validation Messaging)
{
	var tf = document.getElementById(elem);
	var regularExpression = new RegExp;
	if(elem == "email")
  {	//Prevents the use of single quotes, spaces and double quotes in the email field
		//g(global) = sets the expression restrictions on everything within the string, not just the 1st character
		//i(inCaseSensitive) = does not matter if the string has upper or lowercase characters
		regularExpression = /[' "]/gi;
	}
  else if(elem == "username")
  {
		//Restricts everything EXCEPT letters and numbers
		regularExpression = /[^a-z0-9]/gi;
	}
	tf.value = tf.value.replace(regularExpression, "");
}

function emptyElement(element) //Will empty whatever element is passed through
{
	document.getElementById(element).innerHTML = ""; //Sets the data of the element to nothing
}

function checkusername()
{
	var u = document.getElementById("username").value; //u becomes the username field of the form
	if(u != "")
	{
		document.getElementById("unamestatus").innerHTML = 'checking ...'; //Consider adding an image tage or gif for coolness
		var ajax = ajaxObj("GET", "register?usernamecheck="+u);
    ajax.onreadystatechange = function()
		{
      if(ajaxReturn(ajax) == true)
			{
          document.getElementById("unamestatus").innerHTML = ajax.responseText;
      }
    }
    ajax.send();
	}
}

function signup()
{
	var u = document.getElementById("username").value;
	var e = document.getElementById("email").value;
	var p1 = document.getElementById("password").value;
	var p2 = document.getElementById("password_confirmation").value;

	var status = document.getElementById("status");
	//(--Custom Validation--)
	if(u == "" || e == "" || p1 == "" || p2 == "")
	//if any of the above elements are empty
	{
		status.innerHTML = "Fill out all of the form data";
	}
	else if(p1 != p2)
	//if the passwords do not match
	{
		status.innerHTML = "Your password fields do not match";
	}
	else if( document.getElementById("terms").style.display == "none")
	//if the terms have not been views yet, display is still none
	{
		status.innerHTML = "Please view the terms of use";
	}
	else
	{
		document.getElementById("signupbtn").style.display = "none";
		status.innerHTML = 'please wait ...';
		var ajax = ajaxObj("GET", "register?u="+u+"&e="+e+"&p="+p1);
		ajax.onreadystatechange = function()
		{
      if(ajaxReturn(ajax) == true)
			{
        if(ajax.responseText != "signup_success")
				{
					status.innerHTML = ajax.responseText;
					document.getElementById("signupbtn").style.display = "block";
				}
				else if(ajax.responseText == "signup_success")
				{
					window.scrollTo(0,0);
					document.getElementById("signupform").innerHTML = "OK "+u+", check your email inbox and junk mail box at <u>"+e+"</u> in a moment to complete the sign up process by activating your account. You will not be able to do anything on the site until you successfully activate your account.";
					document.getElementById("terms").style.display = "none";
					document.getElementById("termsLink").style.display = "none";
				}
      }
    }
    ajax.send();
	}
}
function openTerms() //Opens the terms of conditions element for view in order to sign up
{
	document.getElementById("terms").style.display = "block";
	emptyElement("status");
}
/* function addEvents() //You can add your event listeners here in the javascript instead of in the html elemtns (recommended)
{
	_("elemID").addEventListener("click", func, false);
}
window.onload = addEvents; */
