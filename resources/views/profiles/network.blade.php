@extends('layouts.master')

@section('title')
   {{$profileOwner->name}}'s Network | OneTribe
@endsection
<?php
  use App\Friend;
  use App\User;

  if($profileOwner->id == Auth::user()->id)
  {
    $isOwner = true;
    $networkHead = "Your Network";
  }
  else
  {
    $isOwner = false;
    $networkHead = "The Network of ".$profileOwner->name;
  }

  $friends = Friend::where('user1','=',$profileOwner->name)
  ->where('accepted','=','1')
  ->orWhere('user2','=',$profileOwner->name)
  ->where('accepted','=','1')->orderBy('created_at','desc')->get();

  $friendships = "";
  if($friends == "[]")
  {
    $friendships = 'No connections to yet...';
  }
  else
  {
    foreach ($friends as $friend)
    {
      $reqID = $friend->id;
      $user1 = $friend->user1;
      $user2 = $friend->user2;
      $datemade = $friend->created_at;
      $datemade = strftime("%B %d", strtotime($datemade));

      $user1avatar = User::where('name','=',$user1)->first();//First gives you an object instead of an array
      $user1avatar = $user1avatar ->avatar;

      $user2avatar = User::where('name','=',$user2)->first();//First gives you an object instead of an array
      $user2avatar = $user2avatar ->avatar;

      $user1pic = '<img src="/uploads/user/'.$user1.'/images'.'/'.$user1avatar.'" alt="'.$user1.'" class="user_pic">';
      $user2pic = '<img src="/uploads/user/'.$user2.'/images'.'/'.$user2avatar.'" alt="'.$user2.'" class="user_pic">';

      if($profileOwner->name == $user1)
      {
        if($user2avatar == NULL)
        {
          $picURL = "/images/Default.jpg";
          $user2pic = '<img src="'.$picURL.'" alt="'.$user2.'" class="user_pic">';
        }
        $friendships .= '<div id="friendreq_'.$reqID.'" class="friendrequests">';
        $friendships .= '<a href="/profile/'.$user2.'">'.$user2pic.'</a>';
        $friendships .= '<div class="user_info" id="user_info_'.$reqID.'"><a href="/profile/'.$user2.'">'.$user2.'</a> since '.$datemade.'<br /><br />';
        if($isOwner==true)
        {
          $friendships .= '<button title="'.$user1.'" id="unfriend">Unfriend</button>';
        }
        $friendships .= '</div>';
        $friendships .= '</div>';
      }
      else if($profileOwner->name == $user2)
      {
        if($user1avatar == NULL)
        {
          $picURL = "/images/Default.jpg";
          $user1pic = '<img src="'.$picURL.'" alt="'.$user1.'" class="user_pic">';
        }
        $friendships .= '<div id="friendreq_'.$reqID.'" class="friendrequests">';
        $friendships .= '<a href="/profile/'.$user1.'">'.$user1pic.'</a>';
        $friendships .= '<div class="user_info" id="user_info_'.$reqID.'"><a href="/profile/'.$user1.'">'.$user1.'</a> since '.$datemade.'<br /><br />';
        if($isOwner==true)
        {
          $friendships .= '<button title="'.$user1.'" id="unfriend">Unfriend</button>';
        }
        $friendships .= '</div>';
        $friendships .= '</div>';
      }
    }
  }
?>
@section('content')
  <div id="friendList">
    <h2>{{$networkHead}}</h2>
    <?php echo $friendships; ?>
    <div style="clear:left;">
    </div>
  </div>
@endsection

@section('javascript')
  <script type="text/javascript">
    var token = '{{Session::token()}}';
    var urlf= '{{route('friend')}}';
    var urlb= '{{route('block')}}';

    function toggle(type, user)
    {
      var $tog = $('#'+type);
      var user = user;
      var log = "<?php echo Auth::user()->name?>";
      console.log(type + " " + user + " " + log);
      $tog.html("please wait...");

      if(type == "friend" || type == "unfriend")
      {
        $.ajax(
        {
          method: 'POST',
          url: urlf,
          data: {type: type, user: user, log: log, _token: token}
        }).done(function (msg)
        {
          //console.log(msg['message']);
          if(msg['message'] == "friend_request_sent")
          {
            $tog.html('OK Friend Request Sent');
          }
          else if(msg['message'] == "unfriend_ok")
          {
            $tog.html('Unfriended');//$tog.html('<button id="friend">Request As Friend</button>');
          }
          else
          {
            alert(msg['message']);
            $tog.html('Try again later.')
          }
        });
      }

      if(type == "block" || type == "unblock")
      {
        $.ajax(
        {
          method: 'POST',
          url: urlb,
          data: {type: type, user: user, log: log, _token: token}
        }).done(function (msg)
        {
          //console.log(msg['message']);
          if(msg['message'] == "blocked_ok")
          {
            $tog.html('Blocked');//$tog.html('<button id="unblock">Unblock User</button>');
          }
          else if(msg['message'] == "unblocked_ok")
          {
            $tog.html('Unblocked');//$tog.html('<button id="block">Block User</button>');
          }
          else
          {
            alert(msg['message']);
            $tog.html('Try again later.')
          }
        });
      }
    }

    $(document).ready(function()
    {
      var $unfriend = $('#unfriend');
      var $friend = $unfriend.attr('title');

      $unfriend.on('click',function(){toggle('unfriend',$friend);});
    });
  </script>
@endsection
