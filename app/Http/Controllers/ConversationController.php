<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Conversation;

class ConversationController extends Controller
{
  public function __construct()
  {
      $this->middleware('auth');
  }

  public function index(User $inboxOwner)
  {
    return view('conversations.inbox',compact('inboxOwner'));
  }
}
