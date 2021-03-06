                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                   @extends('layouts.master')
<?php
  use App\Friend;
  use App\User;
  use App\Post;
  use App\Patron;
  use App\Review;
  use App\Profile;
  use Carbon\Carbon;

  $loggedUser = Auth::user()->name;
  $userLogged = User::where('name','=',$loggedUser)->first();
  $profile = Profile::where('user_id','=',$profileOwner->id)->first();
  if($profile == "")
  {
    $profile = Profile::create([
      'user_id' => $profileOwner->id,
    ]);
  }
  $isOwner = false;
  $isFriend = false;
  $who = "";
  $friend_button = '<button class="btn-primary btn-md" disabled>Request As Friend</button>';

  //Pull up the list of the profile owner's friends
  $friends = Friend::where('user2','=',$profileOwner->name)->where('accepted','=','1')
  ->orWhere('user1','=',$profileOwner->name)->where('accepted','=','1')->get();
  //Verify if this profile belongs to the one visiting the page
  if($profileOwner->id == Auth::user()->id)
  {
    $isOwner = true;
    $who = "Your";
  }
  else
  {
    $who = $profileOwner->name."'s";
    //Check to see if the profile owner and logged in user are friends
    $friend_check = Friend::where('user1','=',$loggedUser)->where('user2','=',$profileOwner->name)->where('accepted','=','1')
    ->orWhere('user1','=',$profileOwner->name)->where('user2','=',$loggedUser)->where('accepted','=','1')->get();
    //Friend  and Block button logic for profile
    if($friend_check != "[]")//If the friend check is not empty
    {
      $isFriend = true;
      $friend_button = '<button class="btn-primary btn-md" id="unfriend">Unfriend</button>';
    }
    else
        $friend_button = '<button class="btn-primary btn-md" id="friend">Befriend</button>';
  }
  $posts = Post::where('user_id','=',$profileOwner->id)->get(); //->orderBy('created_at','desc')
?>
@section('title')
  {{$profileOwner->name}} | GloTribe
@endsection

@section('content')                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                
  <h1>{{$who}} Profile</h1>
  @include ('layouts.errors')
  <hr/>
  <div class="row">
    <div class="col-4 avatar">
      <div id="profile_pic_box">
        @if($isOwner==true)
          <!--FORM TO CHANGE AVATAR-->
          <a id="editAvatar" href="#" onclick="return false;" onmousedown="toggleElement('avatar_form')">Edit Avatar</a>
          <form id="avatar_form" enctype="multipart/form-data" method="post" action="/photoSystem/<?php echo Auth::user()->name?>">
            {{csrf_field()}}
            <h4>Change your avatar</h4>
            <input type="file" name="avatar" required>
            <p><input type="submit" value="Upload"></p>
          </form>
        @endif
        <?php
          if($profileOwner->avatar == NULL)
            echo '<img src="/images/Default.jpg" width="245px" height="245px" alt="Profile Picture"><br/>';
          else
            echo '<img src="/uploads/user/'.$profileOwner->name.'/images'.'/'.$profileOwner->avatar.'" width="250px" height="250px" alt="Profile Picture"><br/>';
        ?>
      </div>
      <div id="options">
        <button type="button" data-toggle="modal" data-target="#networkModal" class="btn-primary btn-md">Network</button><br/>
        @if($isOwner == true)
          <button type="button" data-toggle="modal" data-target="#manageModal" class="btn-primary btn-md">Profile Settings</button><br/>
          <!--button type="button" data-toggle="modal" data-target="#slidesModal" class="btn-primary btn-md">Edit Slides</button-->
        @else
          <?php echo $friend_button; ?>
        @endif
      </div>
    </div>
    <div id="photoBanner" class="col-8">
      <h2 style="text-align:right;">{{$who}} Slideshow</h2>
      <hr/>
      <?php
        $oneSlide = "";
        $twoSlide = "";
        $threeSlide = "";
        $fourSlide = "";
        $nothing = "";
        if($profile->slideOne != NULL)
          $oneSlide = '<img class="mySlides" src="/uploads/user/'.$profileOwner->name.'/images'.'/'.$profile->slideOne.'">';
        if($profile->slideTwo != NULL)
          $twoSlide = '<img class="mySlides" src="/uploads/user/'.$profileOwner->name.'/images'.'/'.$profile->slideTwo.'">';
        if($profile->slideThree != NULL)
          $threeSlide = '<img class="mySlides" src="/uploads/user/'.$profileOwner->name.'/images'.'/'.$profile->slideThree.'">';
        if($profile->slideFour != NULL)
          $fourSlide = '<img class="mySlides" src="/uploads/user/'.$profileOwner->name.'/images'.'/'.$profile->slideFour.'">';
        if($profile->slideOne == NULL && $profile->slideTwo == NULL && $profile->slideThree == NULL && $profile->slideFour == NULL)
          $nothing = "&nbsp;&nbsp;&nbsp;&nbsp;".$profileOwner->name." has not uploaded any slides yet...";
      ?>
      <div class="w3-content w3-display-container">
        <?php
          echo $oneSlide;
          echo $twoSlide;
          echo $threeSlide;
          echo $fourSlide;
          echo $nothing;
        ?>
        <!--button id="leftBtn" class="w3-button w3-black w3-display-left">&#10094;</button>
        <button id="rightBtn" class="w3-button w3-black w3-display-right">&#10095;</button-->

        <div class="w3-center w3-display-bottommiddle" style="width:100%">
          <div id="leftBtn" class="w3-left" onclick="plusDivs(-1)">&#10094;</div>
          <div id="rightBtn" class="w3-right" onclick="plusDivs(1)">&#10095;</div>
          <!--span class="w3-badge demo w3-border w3-transparent w3-hover-white" onclick="currentDiv(1)"></span>
          <span class="w3-badge demo w3-border w3-transparent w3-hover-white" onclick="currentDiv(2)"></span>
          <span class="w3-badge demo w3-border w3-transparent w3-hover-white" onclick="currentDiv(3)"></span-->
        </div>

      </div>
    </div>
  </div>
  <hr/>
  <div class="row">
    <!--POSTS-->
    <div id="posts" class="col-4 asset">
      <h3><a href="/postings/{{$profileOwner->name}}">Upcoming Events</a></h3>
      <center><b>Click the post to expand</b></center>
      @if($isOwner == true)
        <center><button type="button" data-toggle="modal" data-target="#postModal" class="btn-primary btn-sm">Create Post</button></center>
      @endif
      <hr/>
      @foreach($posts as $post)
        <?php
          $date = strftime("%b %d %Y | %I:%M %p", strtotime($post->eventDate));
          $now = new dateTime();
          $postTime = new dateTime($post->eventDate);
          $hasPassed = false;
          if($now > $postTime)
            $hasPassed = true;
        ?>
        @if($hasPassed == false)
          <div class='profilePost' data-toggle="modal" data-target="#{{$post->id}}">
            <b>{{$post->title}} |</b> <b>{{$date}}</b>
            <br/>
            <b>Art Group(s): {{$post->artGroup1}} {{$post->artGroup2}}</b>
            <br/>
          </div>

          <div id="{{$post->id}}" class="modal fade" role="dialog">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal">&times;</button>
                  <h4 class="modal-title">{{$post->title}}</h4>
                </div>
                <div class="modal-body">
                  <b>Date and Time: <br/>{{$date}}
                  <hr/>
                    Art Group(s): <br/>{{$post->artGroup1}} {{$post->artGroup2}}
                  <hr/>
                    Address: <br/>{{$post->address}}
                  <hr/>
                    Description: <br/>{{$post->description}}
                  <hr/>
                    Event Members: <br/>{{$post->user->name}}
                    <!--Foreach patron display a link tot hier profile page-->
                    <?php
                      $patrons = Patron::where('post_id','=',$post->id)->where('accepted','=','1')->get();
                      foreach($patrons as $patron)
                      {
                        $patronName = $patron->user->name;
                        if($patronName == $userLogged->name)
                          $patronName = "You";
                        echo "| <a href='/profile/".$patron->user->name."'>".$patronName."</a> ";
                      }
                    ?>
                  </b><br/>
                </div>
                <div class="modal-footer">
                  @if($isOwner == false)
                    <button  id="{{$post->id}}Btn" onclick="joinEvent('{{$post->id}}Btn','{{$post->title}}')" class="btn btn-primary">Join Event</button>
                  @endif
                  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
              </div>
            </div>
          </div>
          <hr/>
        @endif
      @endforeach
    </div>
    <!--VISION-->
    <div id="vision" class="col-4 asset">
      <h3>The Vision</h3>
      @if($isOwner == true)
        <center><b>Go to settings to change your vision</b></center>
      @endif
      <hr/>
      <?php
        if($profile->vision != NULL)
          echo "<center>".$profile->vision."</center>";
        else
          echo "<center>".$profile->user->name." has no vision yet.</center>";
      ?>
    </div>
    <!--REVIEWS-->
    <div id="reviews" class="col-4 asset">
      <h3><a href="/reviews/{{$profileOwner->name}}">Past Events & Reviews</a></h3>
      <center><b>Click the post to expand</b></center>
      <hr/>
      @foreach($posts as $post)
        <?php
          $date = strftime("%b %d %Y | %I:%M %p", strtotime($post->eventDate));
          $now = new dateTime();
          $postTime = new dateTime($post->eventDate);
          $hasPassed = false;
          if($now > $postTime)
            $hasPassed = true;
        ?>
        @if($hasPassed == true)
          <div class='profilePost' data-toggle="modal" data-target="#{{$post->id}}Past">
            <b>{{$post->title}} |</b> <b>{{$date}}</b>
            <br/>
            <b>Art Group(s): {{$post->artGroup1}} {{$post->artGroup2}}</b>
            <br/>
          </div>

          <div id="{{$post->id}}Past" class="modal fade" role="dialog">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal">&times;</button>
                  <h4 class="modal-title">{{$post->title}}</h4>
                </div>
                <div class="modal-body">
                  <b>{{$date}} | {{$post->artGroup1}} {{$post->artGroup2}}
                  <hr/>
                    Description: <br/>{{$post->description}}
                  <hr/>
                    Event Members: <br/>{{$post->user->name}}
                    <!--Foreach patron display a link tot hier profile page-->
                    <?php
                      $patrons = Patron::where('post_id','=',$post->id)->where('accepted','=','1')->get();
                      foreach($patrons as $patron)
                      {
                        $patronName = $patron->user->name;
                        if($patron->user->id == $userLogged->id)
                          $patronName = "You";
                        echo "| <a href='/profile/".$patron->user->name."'>".$patronName."</a> ";
                      }
                    ?>
                  <hr/>
                    Reviews:</b>
                    <br/>
                    <div style="overflow-y:auto; height:150px;">
                    <?php
                      $reviews = Review::where('post_id','=',$post->id)->get();
                      if($reviews == "[]" || $reviews == "")
                        echo "No reviews to display yet.";
                      else
                      {
                        foreach($reviews as $review)
                        {
                          $reviewerName = $review->user->name;
                          if($review->user->id == $userLogged->id)
                            $reviewerName = "You";
                          echo "<b>".$reviewerName."</b> | ".$review->rating."/5 : ".$review->review."<br/>";
                        }
                      }
                    ?>
                  </div>
                </div>
                <div class="modal-footer">
                  <?php
                    $canReview = Patron::where('post_id','=',$post->id)->where('user_id','=',$userLogged->id)->where('accepted','=','1')->first();
                    $hasReview = Review::where('post_id','=',$post->id)->where('user_id','=',$userLogged->id)->first();
                  ?>
                  @if($canReview != "")
                    @if($hasReview == "")
                    <button id="{{$post->id}}RvwBtn" type="button"
                      class="btn btn-primary" data-toggle="modal"
                      data-target="#{{$post->id}}PastReview">Review Event
                    </button>
                    @endif
                  @endif
                  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
              </div>
            </div>
          </div>
          <div id="{{$post->id}}PastReview" class="modal fade" role="dialog">
            <div class="modal-dialog">
              <div style="background-color:black; color:white;" class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal">&times;</button>
                  <h4 class="modal-title">Leave a Review!</h4>
                </div>
                <div class="modal-body">
                  <form method="POST" action="/reviewSystem">
                    {{csrf_field()}}
                    <input type="hidden" name="userName" value="{{Auth::user()->name}}">
                    <input type="hidden" name="postID" value="{{$post->id}}">
                    <div class="form-group">
                      <label for="picture"><b>How would you rate your experience?</b></label><br/>
                      <select name="rating" required>
                        <option value="1">1-Terrible</option>
                        <option value="2">2-Poor</option>
                        <option value="3" selected >3-Could have been better</option>
                        <option value="4">4-Good</option>
                        <option value="5">5-Incredible</option>
                      </select>
                    </div>
                    <div class="form-group">
                      <label for="review"><b>Please provide some details about your experience with this event.</b></label><br/>
                      <textarea rows="3" cols="45" name="review" required></textarea>
                    </div>
                    <div class="form-group">
                      <button type="submit" class="btn btn-default">
                        Submit Review
                      </button>
                      <span id="status"></span>
                    </div>
                  </form>
                </div>
                <div class="modal-footer">
                  <?php $canReview = Patron::where('post_id','=',$post->id)->where('user_id','=',$userLogged->id)->where('accepted','=','1')->first(); ?>
                  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
              </div>
            </div>
          </div>
          <hr/>
        @endif
      @endforeach
    </div>
  </div>

  <!--MODALS-->
  <!--Network Modal-->
  <div id="networkModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">{{$who}} Network</h4>
        </div>
        <div class="modal-body">
          <?php
            if($friends == "[]")
            {
              if($isOwner == "true")
                echo "Nothing yet, you should send out some friend requests.";
              else
                echo "Nothing yet, you should befriend ".$profileOwner->name;
            }
            foreach($friends as $friend)
            {
              $buddy = "";
              if($friend->user1 == $profileOwner->name)
              {
                $buddy = $friend->user2;
              }
              else if($friend->user2 == $profileOwner->name)
              {
                $buddy = $friend->user1;
              }
              $guy =  User::where('name','=',$buddy)->first();
              $user1avatar = $guy ->avatar;
              $user1pic = '<img src="/uploads/user/'.$guy->name.'/images'.'/'.$user1avatar.'" alt="'.$guy->name.'" class="user_pic">';
          		if($user1avatar == NULL)
              {
                $picURL = "/images/Default.jpg";
          			$user1pic = '<img src="'.$picURL.'" alt="'.$guy->name.'" class="user_pic">';
          		}

              echo '<div class="friendrequests">
                      <a href="/profile/'.$guy->name.'">'.$user1pic.'</a>
                      <div class="user_info"><b><p>'.$guy->name.'</p></b>
                      </div>
                    </div><hr/>';
            }
          ?>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>
  <!--Post Modal-->
  <div id="postModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Make a New Post</h4>
        </div>
        <div class="modal-body">
        <form id="postContentForm" enctype="multipart/form-data" method="post" action="/postSystem">
          {{csrf_field()}}
          <input type="hidden" name="postAuthor" value="{{Auth::user()->name}}">
          <div class="form-group">
            <label for="title"><b>Please select up to 2 art groups for your event.</b></label><br/>
            <input type="checkbox" id="music" value="music"> Music<br/>
            <input type="checkbox" id="modeling" value="modeling"> Modeling<br/>
            <input type="checkbox" id="photography" value="photography"> Photography<br/>
            <input type="checkbox" id="illustration" value="illustration"> Illustration<br/>
            <input type="checkbox" id="film" value="film"> Film<br/>
            <input type="checkbox" id="other" value="other"> Other<br/>
          </div>
          <div class="form-group">
            <label for="title"><b>Please provide a title for your event.</b></label><br/>
            <input type="text" name="title" required>
          </div>
          <!--div class="form-group">
            <label for="picture"><b>Upload a picture for your event (Optional)</b></label><br/>
            <input type="file" class="form-control element" name="picture" placeholder="Upload File">
          </div-->
          <div class="form-group">
            <label for="description"><b>Enter a description of your event.</b></label>
            <input type="text" class="form-control element" name="description" required>
          </div>
          <div class="form-group">
            <label for="address"><b>Enter an address for your event.</b></label>
            <input type="text" class="form-control element" name="address" placeholder="Street, City, State Zip" required>
          </div>
          <div class="form-group">
            <label for="time"><b>Select a time for your event</b></label>
            <input type="datetime-local" class="form-control element" name="time" required>
          </div>
          <div class="form-group">
            <button type="submit" id="eventButton" class="btn btn-default">
              Create Event
            </button>
            <button class="btn btn-default" type="reset">Reset</button>
            <span id="status"></span>
          </div>
        </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>
  <!--Post Modal-->
  <div id="manageModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Profile Content</h4>
        </div>
        <div class="modal-body">
        <form id="postContentForm" enctype="multipart/form-data" method="post" action="/profileSystem">
          {{csrf_field()}}
          <input type="hidden" name="profileOwner" value="{{$profileOwner->id}}">

          <?php
            $slideOne = "Currently: empty";
            $slideTwo = "Currently: empty";
            $slideThree = "Currently: empty";
            $slideFour = "Currently: empty";

            if($profile->slideOne != NULL)
              $slideOne = $profile->slideOne;
            if($profile->slideTwo != NULL)
              $slideTwo = $profile->slideTwo;
            if($profile->slideThree != NULL)
              $slideThree = $profile->slideThree;
            if($profile->slideFour != NULL)
              $slideFour = $profile->slideFour;
          ?>
          <div class="form-group">
            <label for="picture"><b>Upload pictures for your slide show</b></label><br/>
            {{$slideOne}}<input type="file" class="form-control element" name="picture1" placeholder="Slide One">
            {{$slideTwo}}<input type="file" class="form-control element" name="picture2" placeholder="Slide Two">
            {{$slideThree}}<input type="file" class="form-control element" name="picture3" placeholder="Slide Three">
            {{$slideFour}}<input type="file" class="form-control element" name="picture4" placeholder="Slide Four">
          </div>

          <div class="form-group">
            <label for="review"><b>Enter your vision here.</b></label><br/>
            <textarea rows="3" cols="45" name="vision"></textarea>
          </div>

          <div class="form-group">
            <button type="submit" class="btn btn-default">Update Profile</button>
          </div>
        </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>
@endsection

@section('javascript')
  <script type="text/javascript">
    var token = '{{Session::token()}}';
    var urlf= '{{route('friend')}}';
    var urle= '{{route('events')}}';
    var urlb= '{{route('block')}}';

    //FOR EVERYTHING ELSE
    function joinEvent(button,title)
    {
      var $btn = $('#'+button);
      var log = "<?php echo Auth::user()->name?>";
      $btn.html("please wait...");
      //console.log('Event: '+title+' Joinee: '+log);

      $.ajax(
      {
        method: 'POST',
        url: urle,
        data: {title: title, log: log, _token: token}
      }).done(function (msg)
      {
        //console.log(msg['message']);
        if(msg['message'] == "member_request_sent")
        {
          $btn.html('OK Request Sent');
        }
        else
        {
          //alert(msg['message']);
          $btn.html(msg['message'])
        }
      });
    }

    function toggle(type)
    {
      var $tog = $('#'+type);
      var user = "<?php echo $profileOwner->name?>";
      var log = "<?php echo Auth::user()->name?>";
      //console.log(type + " " + user + " " + log);
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
    }

    $(document).ready(function()
    {
      var $friend = $('#friend');
      var $unfriend = $('#unfriend');
      var $edit = $('#editAvatar');

      $edit.hide();
      $friend.on('click', function(){toggle('friend');});
      $unfriend.on('click',function(){toggle('unfriend');});

      $('#profile_pic_box').mouseover(function(){$edit.show();});
      $('#profile_pic_box').mouseout(function(){$edit.hide();});

      $('#postContentForm').on('submit',function()
      {
          if($('#music').prop('checked'))
          {
            var Music = $("<input>").attr("type", "hidden").attr("name", "Music").val("true");
            $('#postContentForm').append($(Music));
          }
          if($('#modeling').prop('checked'))
          {
            var Modeling = $("<input>").attr("type", "hidden").attr("name", "Modeling").val("true");
            $('#postContentForm').append($(Modeling));
          }
          if($('#photography').prop('checked'))
          {
            var Photography = $("<input>").attr("type", "hidden").attr("name", "Photography").val("true");
            $('#postContentForm').append($(Photography));
          }
          if($('#illustration').prop('checked'))
          {
            var Illustration = $("<input>").attr("type", "hidden").attr("name", "Illustration").val("true");
            $('#postContentForm').append($(Illustration));
          }
          if($('#film').prop('checked'))
          {
            var Film = $("<input>").attr("type", "hidden").attr("name", "Film").val("true");
            $('#postContentForm').append($(Film));
          }
          if($('#other').prop('checked'))
          {
            var Other = $("<input>").attr("type", "hidden").attr("name", "Other").val("true");
            $('#postContentForm').append($(Other));
          }
      });

      $('#leftBtn').on('click',function(){plusDivs(-1);});
      $('#rightBtn').on('click',function(){plusDivs(1);});

      //FOR THE CAROUSEL
      var slideIndex = 1;
      showDivs(slideIndex);

      function plusDivs(n)
      {
          showDivs(slideIndex += n);
      }
      function showDivs(n)
      {
          var i;
          var x = document.getElementsByClassName("mySlides");
          if (n > x.length) {slideIndex = 1}
          if (n < 1) {slideIndex = x.length} ;
          for (i = 0; i < x.length; i++) {
              x[i].style.display = "none";
          }
          x[slideIndex-1].style.display = "block";
      }
      var slideIndex = 0;
      carousel();

      function carousel()
      {
          var i;
          var x = document.getElementsByClassName("mySlides");
          for (i = 0; i < x.length; i++)
          {
            x[i].style.display = "none";
          }
          slideIndex++;
          if (slideIndex > x.length) {slideIndex = 1}
          x[slideIndex-1].style.display = "block";
          setTimeout(carousel, 5000);
      }
    });
  </script>
@endsection
