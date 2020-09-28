<?php

namespace App\Http\Controllers\Teams;

use App\Http\Controllers\Controller;
use App\Http\Requests\Teams\StoreTeam;
use App\Http\Requests\Teams\UpdateTeam;
use App\Http\Resources\TeamResource;
use App\Models\Team;
use App\Repositories\Contracts\ITeam;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TeamController extends Controller
{

    protected $teams;

    public function __construct(ITeam $teams)
    {
        $this->teams = $teams;
    }


    public function store(StoreTeam $request)
    {
        $team = $this->teams->create([
            'owner_id' => auth()->id(),
            'name' => $request->name,
            'slug' => Str::slug($request->name)
        ]);

        return new TeamResource($team);

    }

    public function find(Team $team)
    {
        return new TeamResource($team);
    }

    public function index()
    {

    }
    public function fetchUserTeams()
    {
        return TeamResource::collection($this->teams->fetchUserTeams());

    }

    public function update(UpdateTeam $request,Team $team)
    {
        $team = $this->teams->update($team,[
           'name' => $request->name,
           'slug'=> Str::slug($request->name),
        ]);

        return new TeamResource($team);

    }

    public function destroy()
    {

    }

    public function findBySlug()
    {

    }
}
