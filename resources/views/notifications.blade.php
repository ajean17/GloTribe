@extends('layouts.master')

@section('title')
  Notifications | OneTribe
@endsection

<?php
  use App\User;
  use App\Friend;
  use App\Notification;
  use App\Patron;

  $patrons_list = "";
  $log_username = Auth::user()->name;
  $authorized = false;
  //Update the user's notescheck property, to signal that the notifications have been checked
  User::where('name','=',$log_username)->update(array('notescheck'=> Carbon\Carbon::now()));

  if($log_username == $User->name)
  {
    $authorized = true;
    $patrons = Patron::where('accepted','=',"0")->get();
    $picURL = "";
    //echo $notes;
    if($patrons == "[]")
    {
    	$patrons_list = "You do not have any event requests";
    }
    else
    {
      foreach ($patrons as $patron)
      {
        $postAuthor = $patron->post->user->name;
        if($postAuthor == $User->name)
        {
          $patronID = $patron->id;
      		$user1 = $patron->user->name;
      		$datemade = $patron->created_at;
      		$datemade = strftime("%B %d", strtotime($datemade));
      		$user1avatar = User::where('name','=',$user1)->first();//First gives you an object instead of an array
          $user1avatar = $user1avatar ->avatar;
          $user1pic = '<img src="/uploads/user/'.$user1.'/images'.'/'.$user1avatar.'" alt="'.$user1.'" class="user_pic">';
      		if($user1avatar == NULL)
          {
            $picURL = "/images/Default.jpg";
      			$user1pic = '<img src="'.$picURL.'" alt="'.$user1.'" class="user_pic">';
      		}
      		$patrons_list .= '<div id="friendreq_'.$patronID.'" class="friendrequests">';
      		$patrons_list .= '<a href="/profile/'.$user1.'">'.$user1pic.'</a>';
      		$patrons_list .= '<div class="user_info" id="user_info_'.$patronID.'">'.$datemade.' | <a href="/profile/'.$user1.'">'.$user1.'</a> wishes to be part of <b>'.$patron->post->title.'</b><br /><br />';
      		$patrons_list .= '<button onclick="eventReqHandler(\'accept\',\''.$patronID.'\',\''.$user1.'\',\'user_info_'.$patronID.'\')">Accept</button> or ';
      		$patrons_list .= '<button onclick="eventReqHandler(\'reject\',\''.$patronID.'\',\''.$user1.'\',\'user_info_'.$patronID.'\')">Reject</button>';
      		$patrons_list .= '</div>';
      		$patrons_list .= '</div><hr/>';
        }
      }
      if($patrons_list == "")
        $patrons_list = "You do not have any event requests";
    }

    /*FRIEND REQUESTS**/
    $friend_requests = "";
    $requests = Friend::where('user2','=',$User->name)->where('accepted','=','0')->orderBy('created_at','asc')->get();
    if($requests == "[]")
    	$friend_requests = 'No friend requests';
    else
    {
      foreach ($requests as $request)
      {
        $reqID = $request->id;
    		$user1 = $request->user1;
    		$datemade = $request->created_at;
    		$datemade = strftime("%B %d", strtotime($datemade));
    		$user1avatar = User::where('name','=',$user1)->first();//First gives you an object instead of an array
        $user1avatar = $user1avatar ->avatar;
        $user1pic = '<img src="/uploads/user/'.$user1.'/images'.'/'.$user1avatar.'" alt="'.$user1.'" class="user_pic">';
    		if($user1avatar == NULL)
        {
          $picURL = "/images/Default.jpg";
    			$user1pic = '<img src="'.$picURL.'" alt="'.$user1.'" class="user_pic">';
    		}
    		$friend_requests .= '<div id="friendreq_'.$reqID.'" class="friendrequests">';
    		$friend_requests .= '<a href="/profile/'.$user1.'">'.$user1pic.'</a>';
    		$friend_requests .= '<div class="user_info" id="user_info_'.$reqID.'">'.$datemade.' <a href="/profile/'.$user1.'">'.$user1.'</a> requests friendship<br /><br />';
    		$friend_requests .= '<button onclick="friendReqHandler(\'accept\',\''.$reqID.'\',\''.$user1.'\',\'user_info_'.$reqID.'\')">Accept</button> or ';
    		$friend_requests .= '<button onclick="friendReqHandler(\'reject\',\''.$reqID.'\',\''.$user1.'\',\'user_info_'.$reqID.'\')">Reject</button>';
    		$friend_requests .= '</div>';
    		$friend_requests .= '</div><hr/>';
      }
    }
  }

?>

@section('content')
  @if($authorized == true)
    <div id="notesBox"><h2>Event Requests</h2><hr/><?php echo $patrons_list; ?></div>
    <div id="friendReqBox"><h2>Friend Requests</h2><hr/><?php echo $friend_requests; ?></div>
    <div style="clear:left;"></div>
  @elseif($authorized == false)
    <h1>Where do you think you are going?</h1>
  @endif
@endsection

@section('javascript')
<script type="text/javascript">
  var token = '{{Session::token()}}';
  var url= '{{route('friend')}}';
  var urle= '{{route('events')}}';

  function eventReqHandler(action,reqid,user,elem)
  {
    var $elem = $('#'+elem);
    $elem.html("processing...");
    //console.log("Action "+action+" ReqID: "+reqid+" User: "+user+" elem "+elem);

    $.ajax(
    {
      method: 'POST',
      url: urle,
      data: {action: action, reqid: reqid, _token: token}
    }).done(function (msg)
    {
      //console.log(msg['message']);
      if(msg['message'] == "accepted")
        $elem.html("<b>Request Accepted!</b><br/>");//$tog.html('<button id="unblock">Unblock User</button>');
      else if(msg['message'] == "rejected")
        $elem.html("<b>Request Rejected</b><br/>You chose to reject support from this user");//$tog.html('<button id="block">Block User</button>');
      else
        $elem.html(msg['message'])
    });
  }

  function friendReqHandler(action,reqid,user1,elem)
  {
    //document.getElementById(elem).innerHTML = "processing ...";
    var $elem = $('#'+elem);
    var log = "<?php echo Auth::user()->name?>";

    $elem.html("processing...");
    //console.log("Action "+action+" ReqID "+reqid+" User1 "+user1+" elem "+elem);
    $.ajax(
    {
      method: 'POST',
      url: url,
      data: {action: action, reqid: reqid, user1: user1, log: log, _token: token}
    }).done(function (msg)
    {
      console.log(msg['message']);
      if(msg['message'] == "accepted")
        $elem.html("<b>Request Accepted!</b><br/>Your are now friends");//$tog.html('<button id="unblock">Unblock User</button>');
      else if(msg['message'] == "rejected")
        $elem.html("<b>Request Rejected</b><br/>You chose to reject friendship with this user");//$tog.html('<button id="block">Block User</button>');
      else
        $elem.html(msg['message'])
    });
  }
</script>
@endsection
