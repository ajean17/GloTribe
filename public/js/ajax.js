/*
  The following functions simply ajax requests
  Example Code of Implementation
  <script>
    var ajax = ajaxObj("GET","/parser?data=MY AJAX WORKS");//saves a new AJAX mechanism to the variable AJAX
    ajax.onreadystatechange = function()
                              {
                                if(ajaxReturn(ajax)==true)//When data is sent and returned
                                {
                                  alert(ajax.responseText);
                                }
                              }
    ajax.send();
  </script>
*/
function ajaxObj(method, url)//only takes the method and which page to send the data to
{
  var ajax = new XMLHttpRequest();//saves a new AJAX mechanism to the variable AJAX
  ajax.open(method, url, true);
  ajax.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  return ajax;
}

//ajax.send("name=Alvin&country=USA");//Send POST variable if you are using POST method
//ajax.send("");//If you are using GET, you will be sending null instead of variables
/*
AJAX READY STATES
0 = UNSENT (A request that has not called open() yet)
1 = OPENED (Opened but not sent)
2 = HEADERS RECIEVED (Headers and status recieved)
3 = LOADING (Downloading responseText)
4 = DONE (Data transmission complete)

status
200 = PHP Sent the ajax back with a positive message
*/

function ajaxReturn(ajax)
{
  if(ajax.readyState == 4 && ajax.status == 200)//When data is sent and returned
  {
    return true;
  }
}
