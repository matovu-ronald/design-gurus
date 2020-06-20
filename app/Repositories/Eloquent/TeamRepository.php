<?php

namespace App\Repositories\Eloquent;

use App\Models\Team;
use App\Repositories\Contracts\TeamInterface;
use App\Repositories\Eloquent\BaseRepository;

class TeamRepository extends BaseRepository implements TeamInterface
{
    /**
     *  Fetch all the teams for current logged in user.
     */
    public function fetchUserTeams()
    {
        return auth()->user()->teams;
    }

    /**
     * Get model for the repository
     */
    public function model()
    {
        return Team::class;
    }
}
