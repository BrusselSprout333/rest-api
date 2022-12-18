<?php
declare(strict_types=1);

namespace App\Listeners;

use App\Events\CreateLinkEvent;
use App\Http\Controllers\NotificationsController;

class CreateLinkListener
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
     * @param  object  $event
     * @return void
     */
    public function handle(CreateLinkEvent $event)
    {
        $this->notification->linkCreated($event->email, $event->phone, $event->originalLink);
    }
}
