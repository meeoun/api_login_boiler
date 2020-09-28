<?php

namespace App\Repositories\Contracts;


use App\Models\Chat;

interface IChat
{
    public function createParticipants(Chat $chat,array $data);
    public function getUserChats();
    public function messages(Chat $chat);
}

