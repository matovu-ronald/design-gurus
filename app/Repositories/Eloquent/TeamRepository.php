<?php

namespace App\Repositories\Eloquent;

use App\Models\Team;
use App\Repositories\Contracts\TeamInterface;

class TeamRepository extends BaseRepository implements TeamInterface
{
    /**
     *  Fetch all the teams for current logged in user.
     */
    public function fetchUserTeams()
    {
    }

    /**
     * Get model for the repository.
     */
    public function model()
    {
        return Team::class;
    }
}
