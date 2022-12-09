<?php

namespace App\Listeners;

use App\Events\UpdateLinkEvent;
use App\Http\Controllers\NotificationsController;
// use Illuminate\Contracts\Queue\ShouldQueue;
// use Illuminate\Queue\InteractsWithQueue;
// use Illuminate\Support\Facades\Mail;
// use App\Mail\UpdateLinkMail;
// use Illuminate\Support\Facades\DB;
// use App\Helpers\Utilites\SmsCredentials;
// use Vonage\SMS\Message\SMS;

class UpdateLinkListener
{
    //private SmsCredentials $credentials;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(private NotificationsController $notification)
    {
        //$this->credentials = $credentials;
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\UpdateLinkEvent  $event
     * @return void
     */
    public function handle(UpdateLinkEvent $event)
    {
        $this->notification->linkUpdated($event->email, $event->phone);
    }
}
