<?php

namespace App\Providers;

use App\Events\CreateLinkEvent;
use App\Events\DeleteLinkEvent;
use App\Events\UpdateLinkEvent;
use App\Listeners\CreateLinkListener;
use App\Listeners\DeleteLinkListener;
use App\Listeners\UpdateLinkListener;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        CreateLinkEvent::class => [
            CreateLinkListener::class,
        ],
        UpdateLinkEvent::class => [
            UpdateLinkListener::class,
        ],
        DeleteLinkEvent::class => [
            DeleteLinkListener::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     *
     * @return bool
     */
    public function shouldDiscoverEvents()
    {
        return false;
    }
}
