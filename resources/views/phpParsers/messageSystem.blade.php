<?php

  use App\Conversation;
  use App\Dialogue;

  if(isset($_GET['username']) && isset($_GET['talkTo']) && isset($_GET['message']))
  {
    //Save message
    $username = stripcslashes(htmlspecialchars($_GET['username']));
    $talkTo = stripcslashes(htmlspecialchars($_GET['talkTo']));
    $message = stripslashes(htmlspecialchars($_GET['message']));

    if ($message == "" || $username == "" || $talkTo == "")
    {
      die();
    }

    $c = Conversation::create([
      'user1' => $username,
      'user2'=> $talkTo,
      'message' => $message
    ]);

    $d = Dialogue::where('user1','=',$username)
    ->where('user2','=',$talkTo)
    ->orWhere('user2','=',$username)
    ->where('user1','=',$talkTo)->get();

    if($d == "[]")
    {
      Dialogue::create([
        'user1' => $username,
        'user2'=> $talkTo,
        'lastMessage' => $c->created_at
      ]);
    }
    else
    {
      Dialogue::where('user1','=',$username)
      ->where('user2','=',$talkTo)
      ->orWhere('user2','=',$username)
      ->where('user1','=',$talkTo)->update(Array('lastMessage' => $c->created_at));
    }

  }

  if(isset($_GET['username']) && isset($_GET['talkTo']) && isset($_GET['action']))
  {
    //Load Message
    if($_GET['action']=="update")
    {
      $username = stripslashes(htmlspecialchars($_GET['username']));
      $talkTo = stripcslashes(htmlspecialchars($_GET['talkTo']));
      $messages = Conversation::where('user1','=',$username)->where('user2','=',$talkTo)
      ->where('message','!=','')
      ->orWhere('user1','=',$talkTo)->where('user2','=',$username)
      ->where('message','!=','')->orderBy('created_at','asc')->get();

      foreach ($messages as $message)
      {
        echo $message->user1;
        echo "\\";
        echo $message->user2;
        echo "\\";
        echo $message->message;
        echo "\n";
      }
    }

    else
    {
      exit();
    }
  }

?>
