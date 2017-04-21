<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('home');
    }

    public function account(User $User)
    {
      return view('account',compact('User'));
    }

    public function notifications(User $User)
    {
      //$notes = Notification::where('username','=',$User)->orderBy('created_at','desc')->get();
      return view('notifications',compact('User'));
    }

}
