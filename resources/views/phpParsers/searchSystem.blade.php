<?php
  use App\User;
  use App\Dialogue;

  if(isset($_GET['whoSearched']) && isset($_GET['inboxSearch']))
  {
    $criteria = stripcslashes(htmlspecialchars($_GET['inboxSearch']));
    $whoSearched = stripcslashes(htmlspecialchars($_GET['whoSearched']));
    $newTalkTo = User::where('name','=',$criteria)->first();

    if($newTalkTo != "")
    //If the person being searched for does exist
    {
        //echo $newTalkTo->name;
        //echo $newTalkTo;
        $d = Dialogue::where('user1','=',$whoSearched)->where('user2','=',$criteria)
        ->orWhere('user1','=',$criteria)->where('user2','=',$whoSearched)->first();

        if($d != "")
        {
          echo "You already have a dialogue with ".$criteria;
        }
        else
        {
          Dialogue::create([
            'user1' => $whoSearched,
            'user2'=> $criteria,
            'lastMessage' => Carbon\Carbon::now()
          ]);

          echo "new_dialogue";
        }
    }
    else if($newTalkTo == "")
    {
        echo "Sorry, That user does not exist yet.";
    }

  }
?>
