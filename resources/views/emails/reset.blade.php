<?php
  $u = $user->name;
  $e = $user->email;
  $p = $user->password;
  $url = "?u=".$u."&e=".$e;
?>
@component('mail::message')
  # OneTribe | Password Reset!

  A request to change your password has been made.  If this was not you, please disregard this email.

  Username: {{$user->name}}

  Click the Link below to change your password and reclaim your account.
  <button>
    <a href="http://127.0.0.1:8000/reclaim{{$url}}">Reclaim Your Account!</a>
  </button>

  Thanks,<br>
  {{ config('app.name') }}
@endcomponent
