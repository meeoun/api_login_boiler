<?php
namespace App\Repositories\Eloquent;



use App\Models\Chat;
use App\Models\Message;
use App\Repositories\Contracts\IMessage;

class MessageRepository extends BaseRepository implements IMessage

{

    public function model()
    {
        return Message::class;
    }


    public function messages(Chat $chat)
    {
        return $this->model->where('chat_id', $chat->id)->get();
    }
}
