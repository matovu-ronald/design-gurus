<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\Contracts\{
    UserInterface,
    DesignInterface
};

use App\Repositories\Eloquent\{
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
    }
}
