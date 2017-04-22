<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Friend;
use App\Dialogue;
use App\Message;
use App\Post;
use App\Patron;
use App\Review;

use \Storage;
use Carbon\Carbon;

class ParseController extends Controller
{
    //***Find a way to place all parser content within this controller***//
    public function friend(Request $request)
    {
      $message = "Something went wrong";
      if($request->has('type') && $request->has('user'))
    	{
        //The profile owner being added
    		$user = $request['user'];//preg_replace('#[^a-z0-9]#i', '', $_GET['user']);
    		$log_username = $request['log'];//The one logged in
    		//Check to see if the user to befriend or block exists
    		$exists= User::where('name','=',$user)->first();/*->where('activated','=','1')*->get();*/

        if($exists == "")//If nothing matches in the DB stop everything and tell the user
    			$message = "$user does not exist.";

    		if($request['type'] == "friend")
    		//If friend request
    		{
    			//Check to see if the logged in user sent a request to the profile owner already that has been accepted
    			$row_count1 = Friend::where('user1','=',$log_username)
    			->where('user2','=',$user)
    			->where('accepted','=','1')->get();

    			//Check to see if the profile owner has sent a request to the logged in user already that has been accepted
    			$row_count2 = Friend::where('user1','=',$user)
    			->where('user2','=',$log_username)
    			->where('accepted','=','1')->get();

    			//Check to see if the logged in user sent a request to the profile owner already that has not been accepted
    			$row_count3 = Friend::where('user1','=',$log_username)
    			->where('user2','=',$user)
    			->where('accepted','=','0')->get();

    			//Check to see if the profile owner has sent a request to the logged in user already that has not been accepted
    			$row_count4 = Friend::where('user1','=',$user)
    			->where('user2','=',$log_username)
    			->where('accepted','=','0')->get();

    			if ($row_count1 != "[]" || $row_count2 != "[]")//If the profile owner and logged in user are already friends
            $message =  "You are already friends with $user.";

    			else if ($row_count3 != "[]")//If the logged in user has already sent request to the profile owner
    	      $message = "You have a pending friend request already sent to $user.";

    			else if ($row_count4 != "[]")//If the profile owner has already sent a request to the logged in user
    	      $message =  "$user has requested to friend with you first. Check your friend requests.";

    			else//Create a new friendship request between the logged in user and the profile owner
    			{
    				$newFriendship = Friend::create([
    					'user1' => $log_username,
    					'user2' => $user
    				]);
    	      $message = "friend_request_sent";
    			}
    		}
    		else if($request['type'] == "unfriend")
    		{
    			//Check to see if the logged in user and profile owner are currently friends
    			$row_count = Friend::where('user1','=',$user)
    			->where('user2','=',$log_username)
    			->where('accepted','=','1')
    			->orWhere('user1','=',$log_username)
    			->where('user2','=',$user)
    			->where('accepted','=','1')->get();

    			if ($row_count != '[]')//If the two are friends, delete their friendship record
    			{
    				//DB::table('friends')
    				Friend::where('user1','=',$user)
    				->where('user2','=',$log_username)
    				->where('accepted','=','1')
    				->orWhere('user1','=',$log_username)
    				->where('user2','=',$user)
    				->where('accepted','=','1')->delete();

    		    $message = "unfriend_ok";
    		  }
    			else//Otherwide notify the user that they are not even friends
            $message = "No friendship could be found between your account and $user, therefore we cannot unfriend you.";
    		}
    	}

      /*PARSING FOR ACCEPTING OR REJECTING FRIENDSHIPS*/
    	if($request->has('action') && $request->has('reqid') && $request->has('user1'))
    	{
    		$reqid = $request['reqid'];//preg_replace('#[^0-9]#', '', $_GET['reqid']);
    		$user = $request['user1'];//preg_replace('#[^a-z0-9]#i', '', $_GET['user1']);
    		$log_username = $request['log'];//The one logged in
    		$exists = User::where('name','=',$user)->first();//->where('activated','=','1')*->get();

    		if($exists == "")//If nothing matches in the DB stop everything and tell the user
    			$message = $user." does not exist.";

    		if($request['action'] == "accept")
    		{
    			$row_count = Friend::where('user1','=',$user)
    			->where('user2','=',$log_username)
    			->where('accepted','=','1')
    			->orWhere('user1','=',$log_username)
    			->where('user2','=',$user)
    			->where('accepted','=','1')->get();

    	    if ($row_count != "[]")
            $message = "You are already friends with $user.";
    			else
    			{
    				Friend::where('id','=',$reqid)
    				->where('user1','=',$user)
    				->where('user2','=',$log_username)
    				->update(array('accepted' => '1'));
            $message = "<b>Request Accepted!</b><br />Your are now friends...";
    			}
    		}
    		else if($request['action'] == "reject")
    		{
    			Friend::where('user1','=',$user)
    			->where('user2','=',$log_username)
    			->where('accepted','=','0')->delete();
    			 $message = "<b>Request Rejected</b><br />You chose to reject friendship with this user...";
    		}
    	}

      return response()->json(['message' => $message]);
    }
    public function block(Request $request)
    {
      //dd(request('type'));
      return view('/phpParsers.blockSystem');
    }
    public function conversation(Request $request)
    {
      $message = "Something is wrong";

      if($request->has('username') && $request->has('talkTo') && $request->has('message'))//SEND MESSAGE
      {
        //Save message
        $username = stripcslashes(htmlspecialchars($request['username']));
        $talkTo = stripcslashes(htmlspecialchars($request['talkTo']));
        $message = stripslashes(htmlspecialchars($request['message']));

        if ($message == "" || $username == "" || $talkTo == "")
          $message = "";

        $c = Message::create([
          'user1' => $username,
          'user2'=> $talkTo,
          'message' => $message
        ]);

        $d = Dialogue::where('user1','=',$username)
        ->where('user2','=',$talkTo)
        ->orWhere('user2','=',$username)
        ->where('user1','=',$talkTo)->get();

        if($d == "[]")//If no dialogue exists, make one
        {
          Dialogue::create([
            'user1' => $username,
            'user2'=> $talkTo,
            'lastMessage' => $c->created_at
          ]);
        }
        else//Otherwise update the last message
        {
          Dialogue::where('user1','=',$username)
          ->where('user2','=',$talkTo)
          ->orWhere('user2','=',$username)
          ->where('user1','=',$talkTo)->update(Array('lastMessage' => $c->created_at));
        }
        $message = $c->created_at->format('h:i A F jS');
      }

      if($request->has('username') && $request->has('talkTo') && $request->has('action'))//UPDATE MESSAGES
      {
        //Load Message
        if($request['action']=="update")
        {
          $username = stripslashes(htmlspecialchars($request['username']));
          $talkTo = stripcslashes(htmlspecialchars($request['talkTo']));
          $messages = Message::where('user1','=',$username)->where('user2','=',$talkTo)
          ->where('message','!=','')
          ->orWhere('user1','=',$talkTo)->where('user2','=',$username)
          ->where('message','!=','')->orderBy('created_at','asc')->get();
          $message = "";
          foreach ($messages as $mezzage)
          {
            //$date= Carbon::parse($mezzage['created_at']);
            $message = $message.$mezzage->user1;
            $message = $message."\\";
            $message = $message.$mezzage->user2;
            $message = $message."\\";
            $message = $message.$mezzage->message;
            $message = $message."\\";
            $message = $message.$mezzage->created_at->format('h:i A F jS');
            $message = $message."\n";
          }
        }

      }

      return response()->json(['message' => $message]);
    }
    public function search(Request $request)
    {
      $message = "Something is wrong dude.";
      $picture = "Default";
      if($request->has('lat') && $request->has('lng'))//FROM Search Page
      {
        $lat = $request['lat'];
        $long = $request['lng'];
        $posts = Post::whereBetween('lat',[$lat-.01,$lat+.01])->whereBetween('long',[$long-.01,$long+.01])->get();
        if($posts != "[]")
          return response()->json(['message' => $posts]);
        else
          $message = "Possible database issue.";
      }
      if($request->has('search') && $request->has("music") && $request->has("modeling") && $request->has("photography") && $request->has("illustration") && $request->has("film") && $request->has("other"))
      {
        $search = $request['search'];
        $music = $request['music'];
        $modeling = $request['modeling'];
        $photography = $request['photography'];
        $illustration = $request['illustration'];
        $film = $request['film'];
        $other = $request['other'];

        if($search != "empty")//If there is a name in the search, look for the profile that matches
        {
          $user = User::where('name','=',$search)->first();
          if($user == "")
            $message = "Sorry, no users match your search.";
          else
          {
            $posts = Post::where('user_id','=',$user->id)->get();
            if($posts != "[]")
              return response()->json(['message' => $posts,'action' => 'gotPosts']);
            else
              return response()->json(['message' => $user->name,'action' => 'gotUser']);
          }
        }
        else if($music == "false" && $modeling == "false" && $photography == "false" && $illustration == "false" && $film == "false" && $other == "false")
        {
          $posts = Post::all();
          if($posts != "" && $posts != "[]")
            return response()->json(['message' => $posts,'action' => 'gotPosts']);
          else
            $message = "There are no posts available at this time.";
        }
        else
        {
          $musicQ = "";
          $modelingQ = "";
          $photographyQ = "";
          $illustrationQ = "";
          $filmQ = "";
          $otherQ = "";

          if($music == "true")
            $musicQ = 'Music';

          if($modeling == "true")
            $modelingQ = "Modeling";

          if($photography == "true")
            $photographyQ = "Photography";

          if($illustration == "true")
            $illustrationQ = "Illustration";

          if($film == "true")
            $filmQ = "Film";

          if($other == "true")
            $otherQ = "Other";

          $posts = Post::whereIn('artGroup1', array($musicQ,$modelingQ,$photographyQ,$illustrationQ,$filmQ,$otherQ))
                   ->orWhereIn('artGroup2', array($musicQ,$modelingQ,$photographyQ,$illustrationQ,$filmQ,$otherQ))->get();

          if($posts != "[]")
            return response()->json(['message' => $posts,'action' => 'gotPosts']);
          else
            $message = "There are no posts available at this time.";

        }
        //  $message = "Black Trap is pretty tough";
      }
      if($request->has('whoSearched') && $request->has('inboxSearch'))//FROM Inbox Page
      {
        $criteria = stripcslashes(htmlspecialchars($request['inboxSearch']));
        $whoSearched = stripcslashes(htmlspecialchars($request['whoSearched']));
        $newTalkTo = User::where('name','=',$criteria)->first();

        if($newTalkTo != "")
        //If the person being searched for does exist
        {
            $d = Dialogue::where('user1','=',$whoSearched)->where('user2','=',$criteria)
            ->orWhere('user1','=',$criteria)->where('user2','=',$whoSearched)->first();

            if($d != "")
              $message = "You already have a dialogue with ".$criteria;

            else
            {
              Dialogue::create([
                'user1' => $whoSearched,
                'user2'=> $criteria,
                'lastMessage' => Carbon::now()
              ]);

              $message = "new_dialogue";
              if($newTalkTo->avatar != "" && $newTalkTo != NULL)
                $picture = $newTalkTo->avatar;
            }
        }
        else if($newTalkTo == "")
        {
            $message = "Sorry, That user does not exist yet.";
        }
      }
      return response()->json(['message' => $message,'action' => 'gotNothing','picture' => $picture]);
    }
    public function post(Request $request)
    {
      if($request->has('postAuthor'))
      {
        $userName = $request['postAuthor'];
        $music = $request['Music'];
        $modeling = $request['Modeling'];
        $photography = $request['Photography'];
        $illustration = $request['Illustration'];
        $film = $request['Film'];
        $other = $request['Other'];
        $title = $request['title'];
        $picture = $request['picture'];
        $description = $request['description'];
        $address = $request['address'];
        $time = $request['time'];
        $groupCount = 0;
        $group1 = "Other";
        $group2 = "";

        if($music != NULL && $groupCount < 2)
        {
          if($group1 == "Other")
            $group1 = "Music";
          else if($group1 != "")
            $group2 = "Music";
          $groupCount += 1;
        }
        if($modeling != NULL && $groupCount < 2)
        {
          if($group1 == "Other")
            $group1 = "Modeling";
          else if($group1 != "" && $group2 == "")
            $group2 = "Modeling";
          $groupCount += 1;
        }
        if($photography != NULL && $groupCount < 2)
        {
          if($group1 == "Other")
            $group1 = "Photography";
          else if($group1 != "" && $group2 == "")
            $group2 = "Photography";
          $groupCount += 1;
        }
        if($illustration != NULL && $groupCount < 2)
        {
          if($group1 == "Other")
            $group1 = "Illustration";
          else if($group1 != "" && $group2 == "")
            $group2 = "Illustration";
          $groupCount += 1;
        }
        if($film!= NULL && $groupCount < 2)
        {
          if($group1 == "Other")
            $group1 = "Film";
          else if($group1 != "" && $group2 == "")
            $group2 = "Film";
          $groupCount += 1;
        }
        if($other != NULL && $groupCount < 2)
        {
          if($group1 == "Other")
            $group1 = "Other";
          else if($group1 != "" && $group2 == "")
            $group2 = "Other";
          $groupCount += 1;
        }
        //dd($groupCount.$group1.$group2);
        $user = User::where('name','=',$userName)->first();
        $newPost = Post::create([
          'user_id' => $user->id,
          'title' => $title,
          'description' => $description,
          'picture' => $picture,
          'address' => $address,
          'artGroup1' => $group1,
          'artGroup2' => $group2,
          'eventDate' => $time
        ]);
        return redirect()->to('/profile'.'/'.$userName);
      }
      if($request->has('postEditor'))
      {
        $userName = $request['postEditor'];
        return redirect()->to('/postings'.'/'.$userName);
      }
    }
    public function review(Request $request)
    {
      $username = $request['userName'];
      $review = $request['review'];
      $rating = $request['rating'];
      $postID = $request['postID'];

      $user = User::where('name','=',$username)->first();
      $post = Post::where('id','=',$postID)->first();
      $exists = Review::where('post_id','=',$post->id)->where('user_id','=',$user->id)->first();

      if($user == "" || $post == "")
      {
        return back()->withErrors(['message' => 'There was an issue with your review. Please try again later']);
      }
      else if($exists != "")
      {
        return back()->withErrors(['message' => 'You can only leave one review per event.']);
      }
      else if($exists == "")
      {
        $newReview = Review::create([
          'post_id' => $post->id,
          'user_id' => $user->id,
          'rating' => $rating,
          'review' => $review
        ]);
      }

      return redirect()->to('/profile'.'/'.$post->user->name);
    }
    public function events(Request $request)
    {
      $message = "Something is wrong";
      if($request->has('title') && $request->has('log'))
      {
        $title = $request['title'];
        $logged = $request['log'];
        $eventMember = User::where('name','=',$logged)->first();
        $post = Post::where('title','=',$title)->first();
        if($eventMember == "" || $post == "")
          $message = "There is an issue with this event, try again later.";
        else
        {
          if($eventMember->id == $post->user_id)//If the person making the request owns the post
            $message = "There is an issue with this event, try again later.";
          else
          {
            $patronCheck = Patron::where('post_id','=',$post->id)->where('user_id','=',$eventMember->id)->first();
            if($patronCheck != "")
            {
              if($patronCheck->accepted == 0)
                $message = "You have already sent a request to join this event";
              else if($patronCheck->accepted == 1)
                $message = "You are already a part of this event";
              else
                $message = "Uhhh...";
            }
            else
            {
              $patronRequest = Patron::create([
                'post_id' => $post->id,
                'user_id' => $eventMember->id,
              ]);
              $message = "member_request_sent";
            }
          }
        }
      }
      if($request->has('action') && $request->has('reqid'))
      {
        $action = $request['action'];
        $patronID = $request['reqid'];
        $patron = Patron::where('id','=',$patronID)->first();
        $post = Post::where('id','=',$patron->post_id)->first();

        if($patron == "" || $post == "")//If either the request or the post it is for are nonexistant
          $message = "There is a problem with this request, please try again later.";
        else if($patron != "" && $post != "")//Otherwise, if both are valid
        {
          if($action == "accept")//Accept the request
          {
            if($patron->accepted == 1)
              $message = "This request has already been accepted. We apologize for showing it again.";
            else if($patron->accepted == 0)
            {
              Patron::where('id','=',$patronID)->update(array('accepted'=>'1'));
              $message = "accepted";
            }
          }
          else if($action == "reject")//Reject the request and delete it...(May want to prevent spamming requests, do a one and done)
          {
            if($patron->accepted == 1)
              $message = "This request has already been accepted. We apologize for showing it again.";
            else if($patron->accepted == 0)
            {
              Patron::where('id','=',$patronID)->delete();
              $message = "rejected";
            }
          }
        }
      }
      return response()->json(['message' => $message]);
    }
    public function password()
    {
      return view('phpParsers.passwordSystem');
    }

    public function photoHandle(User $User, Request $request)
    {
      //Pull the request object named avatar, and assign its original filename to a variable
      $file = $request->file('avatar');
      $fileName = $file->getClientOriginalName();
      $userName = $User->name;
      //Grab the allowed file types and max file size from the config.app file keys
      $allowedFileTypes = config('app.allowedFileTypes');
      $maxFileSize = config('app.maxFileSize');
      //Assign the validation rules and run the command
      $rules = [
        'avatar' => 'required|mimes:'.$allowedFileTypes.'|max:'.$maxFileSize
      ];
      $this->validate($request,$rules);
      //Grab the destination path variable from the config.app file key
      $destinationPath = config('app.fileDestinationPath').'/'.$userName.'/images'.'/'.$fileName;
      //Move the uploaded file from the temporary location to the folder of choice
      $moveResult = Storage::put($destinationPath, file_get_contents($file->getRealPath()));

      if($User->avatar != NULL)
      //If the user already has an avatar, delete it and replace with the uploaded file
      {
        //delete current photo in its place
        Storage::delete(config('app.fileDestinationPath').'/'.$userName.'/images'.'/'.$User->avatar);
      }
      User::where('name','=',$userName)->update(Array('avatar' => $fileName));
      //Back to the profile page
      return redirect()->to('/profile'.'/'.$userName);
    }

}
