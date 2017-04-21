<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content=""></meta>
    <link rel="icon" href="../../favicon.ico">
    <title>
      @yield('title')
    </title>
    @include('layouts.style')
    @yield('javascript')
  </head>
  <body>
    @include('layouts.header')
    @if ($flash = session('message'))
      <div class="alert alert-success" role=alert>
        {{$flash}}
      </div>
    @endif
    <div class="container">
      <br/>
      @yield('content')
      <br/>
      <br/>
      @include('layouts.footer')
    </div>
  </body>
</html>
