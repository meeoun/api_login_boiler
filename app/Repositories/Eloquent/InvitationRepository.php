<?php
namespace App\Repositories\Eloquent;


use App\Models\Invitation;
use App\Models\Team;
use App\Models\User;
use App\Repositories\Contracts\IInvitation;

class InvitationRepository extends BaseRepository implements IInvitation
{


    public function model()
    {
        return Invitation::class;
    }

    public function addTeamuser(Team $team, int $user_id)
    {

        $team->members()->attach($user_id);
    }

    public function removeTeamuser(Team $team, int $user_id)
    {
       $team->members()->detach($user_id);
    }

}
