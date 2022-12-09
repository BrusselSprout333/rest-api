<?php

namespace App\Providers;

use App\Helpers\Utilites\ShortLinkGenerator;
use App\Helpers\Utilites\SmsCredentials;
use App\Http\Controllers\UserController;
use App\Interfaces\AuthServiceInterface;
use App\Interfaces\LinkServiceInterface;
use App\Interfaces\NotificationsServiceInterface;
use App\Interfaces\UserServiceInterface;
use App\Listeners\CreateLinkListener;
use App\Listeners\UpdateLinkListener;
use App\Listeners\DeleteLinkListener;
use App\Models\Link;
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
        $this->app->bind(CreateLinkListener::class, function () {
            return new CreateLinkListener(SmsCredentials::getInstance());
        });
        $this->app->bind(UpdateLinkListener::class, function () {
            return new UpdateLinkListener(SmsCredentials::getInstance());
        });
        $this->app->bind(DeleteLinkListener::class, function () {
            return new DeleteLinkListener(SmsCredentials::getInstance());
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
