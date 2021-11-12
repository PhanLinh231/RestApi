<?php

namespace App\Providers;

use App\Repositories\Contracts\PasswordRessetRepositoryContract;
use App\Repositories\Contracts\StatusUpdateRepositoryContract;
use App\Repositories\Contracts\UserActivitionTokenRepositoryContract;
use App\Repositories\Contracts\UserRepositoryContract;
use App\Repositories\Contracts\UserUploadRepositoryContract;
use App\Repositories\PasswordRessetRepository;
use App\Repositories\StatusUpdateRepository;
use App\Repositories\UserActivitionTokenRepository;
use App\Repositories\UserRepository;
use App\Repositories\UserUploadRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    public function boot()
    {
        # code...
    }

    public function register()
    {
        $this->app->bind(UserRepositoryContract::class, UserRepository::class);
        $this->app->bind(StatusUpdateRepositoryContract::class, StatusUpdateRepository::class);
        $this->app->bind(UserActivitionTokenRepositoryContract::class, UserActivitionTokenRepository::class);
        $this->app->bind(PasswordRessetRepositoryContract::class,PasswordRessetRepository::class);
        $this->app->bind(UserUploadRepositoryContract::class,UserUploadRepository::class);
    }
}
