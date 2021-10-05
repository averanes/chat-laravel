<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Chat;

class ChatController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function chat_room($roomId)
    {
        $user_a = auth()->user();

        $userIsInThisRoom = false;
        if(is_numeric($roomId)){
            $chat = Chat::find($roomId);

            if (!$chat) {
                $chat = Chat::create(['id' => $roomId]);
            }else{
                abort_unless($chat->isPrivate === 0, 403);
            }

            $userIsInThisRoom = \DB::table("chat_user")->where("user_id", $user_a->id)->where("chat_id", $chat->id)->exists();
        }else{
            $chat = Chat::create([]);
        }


        if (!$userIsInThisRoom) {
            $chat->users()->attach([$user_a->id]);
        }

        return redirect()->route('chat.show', $chat);

    }


    public function chat_with(User $user)
    {

        $user_a = auth()->user();

        $user_b = $user;

        $chat = $user_a->chats()->where('isPrivate', 1)->wherehas('users', function ($q) use ($user_b) {

            $q->where('chat_user.user_id', $user_b->id);

        })->first();

        if (!$chat) {

            $chat = \App\Models\Chat::create(['isPrivate' => 1]);

            $chat->users()->sync([$user_a->id, $user_b->id]);

        }

        return redirect()->route('chat.show', $chat);

    }

    public function show(Chat $chat)
    {

        abort_unless($chat->users->contains(auth()->id()), 403);

        return view('chat', [
            'chat' => $chat
        ]);

    }

    public function get_users(Chat $chat)
    {

        $users = $chat->users;

        return response()->json([
            'users' => $users
        ]);

    }

    public function get_messages(Chat $chat)
    {

        $messages = $chat->messages()->with('user')->get();

        return response()->json([
            'messages' => $messages
        ]);

    }

}
