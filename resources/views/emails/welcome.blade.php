<?php
  $u = $user->name;
  $e = $user->email;
  $p = $user->password;
  $url = "?u=".$u."&e=".$e."&p=".$p;
?>
@component('mail::message')
  # Welcome to One Tribe!

  {{$user->name}}, We are so glad you joined the network.  You are well on your
  way to building your tribe and creating a better world for all to enjoy!

  Click the Link below to confirm your email and get started.
  <button>
    <a href="http://127.0.0.1:8000/activation{{$url}}">Activate Your Account!</a>
  </button>

  Thanks,<br>
  {{ config('app.name') }}
@endcomponent


<?php
  /*@component('mail::button', ['url' => 'http://127.0.0.1:8000/activation'])
  Activate your account!
  @endcomponent*/
?>
