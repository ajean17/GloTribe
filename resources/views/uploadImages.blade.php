@extends('layouts.master')

@section('content')
  <form enctype="multipart/form-data" method="post" action="/imageSystem/">
    {{csrf_field()}}
    <input type="file" name="image" required><br/>
    <input type="submit" value="Upload">
  </form>
@endsection
