<?php

namespace App\Providers;

use App\Helpers\Utilites\ShortLinkGenerator;
use App\Http\Controllers\LinksController;
use App\Http\Controllers\UserController;
use App\Interfaces\AuthServiceInterface;
use App\Interfaces\LinkServiceInterface;
use App\Interfaces\NotificationsServiceInterface;
use App\Interfaces\UserServiceInterface;
use App\Models\Link;
use App\Models\LinkDetails;
use App\Repositories\LinkRepository;
use App\Services\NotificationsService;
use App\Services\AuthService;
use App\Services\LinkService;
use App\Services\UserService;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(AuthServiceInterface::class, function () {
            return new AuthService();
        });
        $this->app->bind(LinkServiceInterface::class, function () {
            return new LinkService(
                new LinkRepository(
                    new Link(),
                    new UserController(new UserService()),
                    new ShortLinkGenerator(new Link())),
                new Link);
        });
        $this->app->bind(UserServiceInterface::class, function () {
            return new UserService();
        });
        $this->app->bind(NotificationsServiceInterface::class, function () {
            return new NotificationsService(new UserController(new UserService()));
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);
    }
}
