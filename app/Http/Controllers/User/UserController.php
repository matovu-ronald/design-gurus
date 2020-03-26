<?php

namespace App\Http\Controllers\User;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Repositories\Contracts\UserInterface;

class UserController extends Controller
{
    protected $users;

    public function __construct(UserInterface $users)
    {
        $this->users = $users;
    }

    public function index()
    {
        $users = $this->users->all();
        return UserResource::collection($users);
    }
}