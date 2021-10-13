<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Events\MessageSent;
use Illuminate\Support\Facades\Log;

class MessageController extends Controller
{

	public function __construct()
	{
		$this->middleware('auth');
	}


	public function sent(Request $request)
	{
        /*Log::info("**** CustomWebSocketHandler MessageController->sent *****");
        Log::info(date("Y-m-d h:i:sa"));*/

		$message = auth()->user()->messages()->create([
			'content' => $request->message,
			'chat_id' => $request->chat_id
		])->load('user');

		broadcast(new MessageSent($message))->toOthers();

		return $message;

	}

}
