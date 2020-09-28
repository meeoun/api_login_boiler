<?php

namespace App\Http\Controllers\Chats;

use App\Http\Controllers\Controller;
use App\Http\Requests\Messages\SendMessage;
use App\Http\Resources\ChatResource;
use App\Http\Resources\MessageResource;
use App\Models\Chat;
use App\Repositories\Contracts\IChat;
use App\Repositories\Contracts\IMessage;
use App\Repositories\Contracts\IUser;
use App\Repositories\Eloquent\Criteria\WithTrashed;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    protected $chats;
    protected $messages;
    protected $users;

    public function __construct(IChat $chats,IMessage $messages, IUser $users)
    {
        $this->chats = $chats;
        $this->messages = $messages;
        $this->users = $users;


    }

    public function sendMessage(SendMessage $request)
    {
        $recipient = $request->recipient;

        $user =$this->users->find(Auth::id());
        $body = $request->body;
        $chat = $user->getUserChat($recipient);

        if(! $chat){
            $chat = $this->chats->create([]);
            $this->chats->createParticipants($chat, [$user->id, $recipient]);
        }

        $message = $this->messages->create([
            'user_id' => $user->id,
            'chat_id' => $chat->id,
            'body' => $body,
            'last_read'=>null
        ]);

        return new MessageResource($message);
    }

    public function getUserChats()
    {
        $chats = $this->chats->getUserChats();

        return ChatResource::collection($chats);
    }


    public function getChatMessages(Chat $chat)
    {

         return MessageResource::collection($this->messages->withCriteria([
             new WithTrashed()
         ])->messages($chat));

    }


    public function markRead(Chat $chat)
    {

        $chat->markRead(auth()->id());

        return response()->json(['message'=>'success'],200);

    }


    public function destroyMessage(Chat $chat)
    {

        $chat->delete();

        return response()->json(['message'=>'success'],200);
    }
}
