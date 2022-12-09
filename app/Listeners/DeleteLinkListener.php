<?php
declare(strict_types=1);

namespace App\Listeners;

use App\Events\DeleteLinkEvent;
use App\Http\Controllers\NotificationsController;
// use Illuminate\Contracts\Queue\ShouldQueue;
// use Illuminate\Queue\InteractsWithQueue;
// use Illuminate\Support\Facades\Mail;
// use App\Mail\DeleteLinkMail;
// use Illuminate\Support\Facades\DB;
// use App\Helpers\Utilites\SmsCredentials;
// use Vonage\SMS\Message\SMS;

class DeleteLinkListener
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
     * @param  object  $event
     * @return void
     */
    public function handle(DeleteLinkEvent $event)
    {
        $this->notification->linkDeleted($event->email, $event->phone);
    }
}
