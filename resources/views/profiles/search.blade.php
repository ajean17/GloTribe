@extends('layouts.master')

@section('title')
  Search | OneTribe
@endsection

@section('content')
  <h1 style="text-align:right; text-decoration: underline;">Find Events</h1>
  <hr/>
  <div class="row">
    <div id="criteria" class="col-3">
      <h3>Search By User</h3>
      <hr/>
      <input type="text" id="searchBar" name="searchBar"
      placeholder="Search for events by user">
      <button id="searchButton">Search</button>
      <div id="criteriaList">
        <hr/>
        <h4>Search By Art Group</h4>
        <div id = "checks">
          Music <input type="checkbox" id="music" name="music"><br/>
          Modeling <input type="checkbox" id="modeling" name="modeling"><br/>
          Photography <input type="checkbox" id="photography" name="photography"><br/>
          Illustration <input type="checkbox" id="illustration" name="illustration"><br/>
          film <input type="checkbox" id="film" name="film"><br/>
          other <input type="checkbox" id="other" name="other"><br/>
        </div>
        <hr/>
        <h4>Search By City</h4>
        <h6 id="cityChoose"></h6>
          <center><select id='cities' name='city'>
            <option value='Atlanta'>Altanta</option>
            <option value='Boston'>Boston</option>
            <option value='Los Angeles'>Los Angeles</option>
            <option value='Miami'>Miami</option>
          </select></center>
        <hr/>
      </div>
    </div>
    <div id="results" class="col-9">
      <center><h3>Locate Your Next Event</h3></center>
      <hr/>
      <div id="map">
      </div>
      <div id="resultList">
        <hr/>
        <h3>Nearby Events</h3>
        <hr/>
        <div id="closePosts">
        </div>
      </div>
    </div>
  </div>
@endsection

@section('javascript')
  <script>
    var token = '{{Session::token()}}';
    var url= '{{route('search')}}';
    var map;
    var myLatLng;
    var iconBase = 'https://maps.google.com/mapfiles/kml/shapes/';
    var Address = '1000 University Center Lane, Lawrenceville, GA 30043';

    $(document).ready(function()
    {
      var geocoder = new google.maps.Geocoder();
      //geoLocationInit();//Sets the map according to user's current position
      getLocale(Address,function(coordinates)//Places a marker based on an address
      {
        var latt = coordinates.lat();
        var longg = coordinates.lng();
        myLatLng = new google.maps.LatLng(latt,longg);
        createMap(myLatLng, "GGC");
      });


      $('#searchBar').on('keypress', function(e){if(e.keyCode == 13){searchNow();}});
      $('#searchButton').on('click', function(){searchNow();});

      function searchNow()
      {
        var music = $('#music').prop('checked');
        var modeling = $('#modeling').prop('checked');
        var photography = $('#photography').prop('checked');
        var illustration = $('#illustration').prop('checked');
        var film = $('#film').prop('checked');
        var other = $('#other').prop('checked');
        var search = $('#searchBar').val();
        if(search == "")
          search = "empty";
        //console.log("Music: "+music+" Modeling: "+modeling+" Photography: "+photography+" Illustration: "+illustration+" Film: "+film+" Other: "+other);
        //console.log("Searching for... "+search);

        $.ajax(
        {
          method: 'POST',
          url: url,
          data: {search: search, music: music, modeling: modeling, photography: photography, illustration: illustration, film: film, other: other, _token: token}
        }).done(function (msg)
        {
          //console.log("Message: "+msg['message']+" Action:"+msg['action']);
          $('#closePosts').html("");
          createMap(myLatLng);
          if(msg['action'] == 'gotPosts')
          {
            $.each(msg['message'],function(i,val)
            {
              var inPast = false;
              var postID = val.id;
              var postTitle = val.title;
              var postDesc = val.description;
              var postAddress = val.address;
              var postDate = new Date(val.eventDate);
              var now = new Date();
              now.setHours(0,0,0,0);
              if(postDate < now)
              {
                inPast = true;
                //console.log(postTitle+" is in the Past");
              }
              if(inPast == false)
              {
                postDate = $.datepicker.formatDate('M dd yy', postDate);
                var postGroup1 = val.artGroup1;
                var postGroup2 = val.artGroup2;

                if(postGroup2 != "" && postGroup2 != undefined)
                  postGroup2 = " | "+postGroup2;
                else
                  postGroup2 = "";

                getLocale(postAddress,function(coordinates)//Places a marker based on an address
                {
                  createMarker(coordinates,postTitle);
                });

                $('#closePosts').append("<div class='showingPost' title='"+postTitle+"'><b>Title: " + postTitle + " | "+postDate+" | <a href='/post/"+postID+"'>Click to view the author's profile for more details and options.</a><br/>Art Group(s): "+postGroup1+postGroup2+"<br/>Address:<br/>"+postAddress+"<br/>Description:<br/>"+postDesc+"</b></div><br/>");
              }
            });
          }
          else if(msg['action'] == 'gotNothing')
          {
            $('#closePosts').append("<div><b>"+msg['message']+"</b></div>");
          }
          else if(msg['action'] == 'gotUser')
          {
            $('#closePosts').append("<center><div><b>"+msg['message']+"</b> does not have any available events right now, you can <a href='/profile/"+msg['message']+"'>click here</a> to view their profile.</div></center>");
          }
        });
      }

      function getLocale(address, callback)
      {
        var coordinates;
        geocoder.geocode({address:address},function(results,status)
        {
          coordinates = results[0].geometry.location;
          //console.log(results[0].geometry.location.lat());
          callback(coordinates);
        });
      }
      function geoLocationInit()
      {
        if(navigator.geolocation)//Tries to get the location of the user if the browser allows it
          navigator.geolocation.getCurrentPosition(success,fail);
        else
          alert("Browser not supported.");
      }
      function success(position)//if the location is found
      {
        //console.log("Success: "+position);
        var latval = position.coords.latitude;
        var longval = position.coords.longitude;
        //console.log("Latitude: "+latval+" Longtitude: "+longval);
        myLatLng = new google.maps.LatLng(latval,longval);
        //console.log(myLatLng);
        createMap(myLatLng);//Display the map with the new found location as the center
        //searchPosts(latval,longval);//Locate all posts that have coordinates within a certain range of the center point
        //nearbyResults(myLatLng,"store");
      }
      function fail()
      {
        alert("Failure playa.");
      }
      function createMap(position, name)
      {
        map = new google.maps.Map(document.getElementById('map'),
        {
          center: position,
          //scrollwheel: false,
          zoom: 8
        });

        var marker = new google.maps.Marker({
          position: position,
          map: map,
          //icon: icn,
          title: name
        });
      }
      function createMarker(latlng,name)
      {
        var marker = new google.maps.Marker({
          position: latlng,
          map: map,
          icon: iconBase + 'info-i_maps.png',
          title: name
        });
      }
    });
  </script>
@endsection
