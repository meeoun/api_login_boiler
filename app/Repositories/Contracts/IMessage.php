<?php

namespace App\Repositories\Contracts;


use App\Models\Chat;

interface IMessage
{

    public function messages(Chat $chat);

}
