<?php

namespace App\Http\Controllers;

// use App\Events\Message;
use App\Events\Message1;
use Illuminate\Http\Request;
use MyEventMessage;
// use App\Events\Message;

class ChatController extends Controller
{
    public function message(Request $request)
    {
        event(new MyEventMessage('hello world'));

        return [];
    }
}