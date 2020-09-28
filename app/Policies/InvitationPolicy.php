<?php

namespace App\Policies;

use App\Models\Invitation;
use App\Models\Team;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class InvitationPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param User $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param User $user
     * @param Invitation $invitation
     * @return mixed
     */
    public function view(User $user, Invitation $invitation)
    {
        //
    }

    /**
     * Determine whether the user can create models.
     *
     * @param User $user
     * @return mixed
     */
    public function create(User $user)
    {
        dd("In Create");
    }


    public function invite(User $user)
    {

        if(! $user->teamOwner(request()->team))
        {
            return false;
        }
        if(request()->team->hasPendingInvite(request()->email)){
            return false;
        };

        return true;
    }

    public function respond(User $user)
    {
        $invitation = request()->invitation;
        if($user->email != $invitation->recipient_email){
            return false;
        }

        return  true;
    }



    /**
     * Determine whether the user can update the model.
     *
     * @param User $user
     * @param Invitation $invitation
     * @return mixed
     */
    public function update(User $user, Invitation $invitation)
    {
        dd("In update");
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param User $user
     * @param Invitation $invitation
     * @return mixed
     */
    public function delete(User $user, Invitation $invitation)
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param User $user
     * @param Invitation $invitation
     * @return mixed
     */
    public function restore(User $user, Invitation $invitation)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param User $user
     * @param Invitation $invitation
     * @return mixed
     */
    public function forceDelete(User $user, Invitation $invitation)
    {
        //
    }
}
