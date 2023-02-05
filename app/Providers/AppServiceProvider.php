<?php

namespace App\Providers;

use App\Helpers\Utilites\NewClass;
use App\Helpers\Utilites\ShortLinkGenerator;
use App\Helpers\Utilites\SmsCredentials;
use App\Helpers\Utilites\SmsMessage;
use App\Http\Controllers\UserController;
use App\Interfaces\AuthServiceInterface;
use App\Interfaces\LinkRepositoryInterface;
use App\Interfaces\LinkServiceInterface;
use App\Interfaces\NotificationsServiceInterface;
use App\Interfaces\UserServiceInterface;
use App\Models\Link;
use App\Repositories\LinkRepository;
use App\Repositories\LinkRepositoryProxy;
use App\Services\NotificationsService;
use App\Services\AuthService;
use App\Services\LinkService;
use App\Services\UserService;
use Database\Factories\LinkFactory;
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
       // $link = new Link();

        // $this->app->bind(LinkFactory::class, function ($link) {
        //     return new ShortLinkGenerator($link);
        // });
        $this->app->when(LinkFactory::class)
            ->needs(ShortLinkGenerator::class)
            ->give(function () {
                return app(ShortLinkGenerator::class);
                //return new ShortLinkGenerator($link);
            });
        

        $this->app->bind(AuthServiceInterface::class, function () {
            return new AuthService();
        });

        $this->app->bind(UserServiceInterface::class, function () {
            return new UserService();
        });

        $this->app->bind(LinkServiceInterface::class, function () {
                return new LinkService(
                    new LinkRepositoryProxy(
                        new LinkRepository(
                            new Link(),
                            new UserController(new UserService()),
                            new ShortLinkGenerator(new Link())),
                            new Link),
                        new Link);
            });

        $this->app->bind(NotificationsServiceInterface::class, function () {
            return new NotificationsService(
                new UserController(new UserService()),
                SmsCredentials::getInstance(),
                new SmsMessage());
        });


        $this->app->bind(LinkRepositoryInterface::class, function () {
            return new LinkRepositoryProxy(
                new LinkRepository(
                    new Link(),
                    new UserController(new UserService()),
                    new ShortLinkGenerator(new Link())),
                new Link()
            );
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
