<header>
  <nav class="navigation">
    <ul>
      @if (Auth::check())
      <li><a href="/home">Home</a></li>
      <li><a href="/profile/{{Auth::user()->name}}">Profile</a></li>
      <li><a href="/inbox/{{Auth::user()->name}}">Inbox</a></li>
      <li><a href="/search">Search</a></li>
      <li><a href="/account/{{Auth::user()->name}}">Account</a></li>
      <li class="right"><a href="/logout">Logout</a></li>
      <li class="right"><a class="user" href="/notifications/{{Auth::user()->name}}">Notifications</a></li>
      @else
      <li><a href="/login">Login</a></li>
      <li><a href="/register">Register</a></li>
      @endif
    </ul>
  </nav>
</header>
