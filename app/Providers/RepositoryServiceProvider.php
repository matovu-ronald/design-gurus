<?php

namespace App\Providers;

use App\Repositories\Contracts\DesignInterface;
use App\Repositories\Contracts\UserInterface;
use App\Repositories\Eloquent\DesignRepository;
use App\Repositories\Eloquent\UserRepository;
use Illuminate\Support\ServiceProvider;
use App\Repositories\Contracts\{
    CommentInterface,
    UserInterface,
    DesignInterface
};

use App\Repositories\Eloquent\{
    CommentRepository,
    UserRepository,
    DesignRepository
};

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->bind(DesignInterface::class, DesignRepository::class);
        $this->app->bind(UserInterface::class, UserRepository::class);
        $this->app->bind(CommentInterface::class, CommentRepository::class);
    }
}
