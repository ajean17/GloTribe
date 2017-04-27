@extends('layouts.master')

@section('title')
  About GloTribe
@endsection

@section('content')
  <h1>About GloTribe</h1>
  <hr/>
  <div class="row">
    <div class="col-12 aboutLeft">
      <img style="text-align:left" src="/images/General.jpg" width="250px" height="250px" alt="General">
      <b><p>
        GloTribe is the social media platform that allows for artists of various disciplines and interests to connect with
        one another and collaborate.  The big idea is that there are a large amount of people with big ideas and small networks.
        As people relocate to areas where they have little to no connections, GloTribe is the bridge between the people who need
        collaborators and ideas.
      </p></b>
    </div>
  </div>
  <div class="row">
    <div class="col-12 aboutRight">
      <img src="/images/Search.png" width="250px" height="250px" alt="Feature One">
      <b><p>
        GloTribe utilizes google maps to help illustrate the availability and abundance of opportunities among members of the
        creative community.  As a member, you can create and find events based on location, art group focus, and time frame.
        GloTribe makes it easier to gain a sense of ease when it comes to connecting with others who value your creativity.
      </p></b>
    </div>
  </div>
  <div class="row">
    <div class="col-12 aboutLeft">
      <img src="/images/Reviews.png" width="250px" height="250px" alt="Feature Two">
      <b><p>
        GloTribe has an intuitive review system that allows users to gain a sense of who they will be collaborating with.
        Users are able to rate and describe thier experiences with each event, along with others who participated in the event.
        Each profile with contain an average score based on reviews on event they participated in.
      </p></b>
    </div>
  </div>
@endsection
