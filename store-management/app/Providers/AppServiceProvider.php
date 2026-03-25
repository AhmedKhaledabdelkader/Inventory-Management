<?php

namespace App\Providers;

use App\Repositories\Contracts\RoleRepositoryInterface;
use App\Repositories\Contracts\TransferIssueRepositoryInterface;
use App\Repositories\Contracts\TransferRepositoryInterface;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Repositories\Eloquents\RoleRepository;
use App\Repositories\Eloquents\TransferIssueRepository;
use App\Repositories\Eloquents\TransferRepository;
use App\Repositories\Eloquents\UserRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {


    $this->app->bind(
    UserRepositoryInterface::class,
    UserRepository::class
);


$this->app->bind(
    RoleRepositoryInterface::class,
    RoleRepository::class
);


$this->app->bind(
    TransferRepositoryInterface::class,
    TransferRepository::class
);



$this->app->bind(
    TransferIssueRepositoryInterface::class,
    TransferIssueRepository::class
);


    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
