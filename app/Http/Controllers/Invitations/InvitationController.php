<?php

namespace App\Http\Controllers\Invitations;

use App\Http\Controllers\Controller;
use App\Http\Requests\Invitations\InviteRequest;
use App\Http\Requests\Invitations\RespondRequest;
use App\Mail\TeamJoinInvitation;
use App\Models\Invitation;
use App\Models\Team;
use App\Repositories\Contracts\IInvitation;
use App\Repositories\Contracts\ITeam;
use App\Repositories\Contracts\IUser;
use http\Env\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class InvitationController extends Controller
{
    protected $invitations;
    protected $teams;
    protected $users;

    public function __construct(IInvitation $invitations, ITeam $teams, IUser $users)
    {
        $this->invitations = $invitations;
        $this->teams = $teams;
        $this->users = $users;
    }

    public function invite(InviteRequest $request, Team $team)
    {
        if($team->hasPendingInvite($request->email))
        {
            return response()->json(['email'=>'Email already has pending invite'],422);
        }

        $recipient = $this->users->findByEmail($request->email);

        if(! $recipient){
            $this->createInvitation(false, $team, $request->email);
            return response()->json(['message'=> 'Invitation sent to user'],200);
        }

        if($team->hasUser($recipient))
        {
            return response()->json(['email'=> 'User already on team'],422);
        }

        $this->createInvitation(true, $team, $request->email);
        return response()->json(['message'=> 'Invitation sent to user'],200);
    }

    public function resend(InviteRequest $invitation)
    {
        $recipient = $this->users->findByEmail($invitation->recipient_email);

        Mail::to($invitation->recipient_email)
            ->send(new TeamJoinInvitation($invitation, !is_null($recipient)));

        return response()->json(['message' => 'invitation resent'],200);
    }

    public function respond(RespondRequest $request, Invitation $invitation)
    {
        $token = $request->token;
        $decision = $request->decision;

        if($token != $invitation->token){
            return response()->json(['message'=>'Invalid Token'], 401);
        }

        if($decision != 'decline'){

            $this->invitations->addTeamuser($invitation->team, auth()->id());
        }

        $invitation->delete();

        return  response()->json(['message'=> 'Successful'],200);
    }

    public function destroy(Invitation $invitation)
    {
        $invitation->delete();

        return response()->json(['message', 'deleted'],200);

    }

    protected function createInvitation(bool $user_exists, Team $team, string $email)
    {
        $invitation = $this->invitations->create([
            'team_id' => $team->id,
            'sender_id' =>auth()->user()->id,
            'recipient_email'=>$email,
            'token' => md5(uniqid(microtime()))
        ]);

        Mail::to($email)->send(new TeamJoinInvitation($invitation, $user_exists));
    }

}
