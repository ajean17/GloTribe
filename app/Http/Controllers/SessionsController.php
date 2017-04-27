<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

class SessionsController extends Controller
{

    public function __construct()
    {
      //store() and create() can't be accessed if not a guest
      $this->middleware('guest')->except('destroy');//;
    }

    public function create()
    {
        return view('sessions.create');
    }

    public function reset()
    {
        return view('sessions.reset');
    }

    public function reclaim()
    {
        return view('sessions.reclaim');
    }

    public function store(Request $request)
    {
      $active = User::where('name','=',request('name'))->first();
      //Check to see if the attempted user has been activated before allowing them to log in.
      if($active != "" && $active->activated != "1")
      {
        return back()->withErrors(['message' => 'Please check your email to activate your account before logging in.']);
      }
      //Attempts to authenticate user and auto signs in
      if(!auth()->attempt(request(['name','password'])))
      {
        return back()->withErrors([
          'message' => 'Wrong username or password, please try again.']);
      }
      $user = request('name');
      //return redirect('/home');
      return redirect("/profile"."/".$user."");//sending the user straight to their profile
    }

    public function destroy()
    {
        auth()->logout();

        return redirect('/');
    }
}
