<?php
declare(strict_types=1);

namespace App\Listeners;

use App\Events\DeleteLinkEvent;
use App\Http\Controllers\NotificationsController;

class DeleteLinkListener
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
    public function handle(DeleteLinkEvent $event)
    {
        $this->notification->linkDeleted($event->email, $event->phone, $event->originalLink);
    }
}
