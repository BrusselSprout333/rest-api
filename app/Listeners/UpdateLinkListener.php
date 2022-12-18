<?php

namespace App\Listeners;

use App\Events\UpdateLinkEvent;
use App\Http\Controllers\NotificationsController;

class UpdateLinkListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(private NotificationsController $notification)
    {
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\UpdateLinkEvent  $event
     * @return void
     */
    public function handle(UpdateLinkEvent $event)
    {
        $this->notification->linkUpdated($event->email, $event->phone, $event->originalLink);
    }
}
