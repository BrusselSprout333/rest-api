<?php

namespace App\Listeners;

use App\Events\UpdateLinkEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;
use App\Mail\UpdateLinkMail;
use Illuminate\Support\Facades\DB;
use App\Helpers\Utilites\SmsCredentials;
use Vonage\SMS\Message\SMS;

class UpdateLinkListener
{
    private SmsCredentials $credentials;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(SmsCredentials $credentials)
    {
        $this->credentials = $credentials;
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\UpdateLinkEvent  $event
     * @return void
     */
    public function handle(UpdateLinkEvent $event)
    {
        //di mechanism
        Mail::to($event->email)->send(new UpdateLinkMail());

        $client = $this->credentials->getClient();
        $client->sms()->send(
            new SMS($event->phone, "Links Shortener", "You've updated a link")
        );

        DB::table('letters')->insert([
            'email' => $event->email,
            'subject' => 'link updation'
        ]);
    }
}
