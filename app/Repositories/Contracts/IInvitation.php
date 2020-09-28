<?php

namespace App\Repositories\Contracts;

use App\Models\Team;
use App\Models\User;

interface IInvitation
{
public function addTeamuser(Team $team, int $user_id);
public function removeTeamuser(Team $team, int $user_id);
}
