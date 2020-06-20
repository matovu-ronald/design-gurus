<?php

namespace App\Http\Controllers\Teams;

use App\Http\Controllers\Controller;
use App\Http\Resources\TeamResource;
use App\Repositories\Contracts\TeamInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TeamController extends Controller
{
    protected $teams;

    public function __construct(TeamInterface $teams)
    {
        $this->teams = $teams;
    }

    /**
     * Get all the teams.
     */
    public function index(Request $request)
    {
    }

    public function findById($id)
    {
        $team = $this->teams->find($id);

        return new TeamResource($team);
    }

    /**
     * Get the authenticated user's teams.
     */
    public function fetchUserTeams()
    {
        $teams = $this->teams->fetchUserTeams();

        return TeamResource::collection($teams);
    }

    /**
     * Get team by slug for the public view.
     */
    public function findBySlug($slug)
    {
        // $this->
    }

    /**
     * Save team to the database.
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => ['required', 'string', 'max:80', 'unique:teams,name'],
        ]);

        // Create team in the database.
        $team = $this->teams->create([
            'owner_id' => auth()->id(),
            'name' => $request->name,
            'slug' => Str::slug($request->name),
        ]);

        // Current user is inserted as
        // team member using boot method in Team model

        return new TeamResource($team);
    }

    /**
     * Update team by $id.
     */
    public function update(Request $request, $id)
    {
        $team = $this->teams->find($id);

        $this->authorize('update', $team);

        $this->validate($request, [
            'name' => ['required', 'max:80', 'string', 'unique:teams,name,'.$id],
        ]);

        $team = $this->teams->update($id, [
            'name' => $request->name,
            'slug' => Str::slug($request->name),
        ]);

        return new TeamResource($team);
    }

    /**
     *  Delete team by $id.
     */
    public function delete($id)
    {
    }
}
