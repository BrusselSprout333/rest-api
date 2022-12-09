<?php
declare(strict_types=1);

namespace App\Listeners;

use App\Events\DeleteLinkEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;
use App\Mail\DeleteLinkMail;
use Illuminate\Support\Facades\DB;
use App\Helpers\Utilites\SmsCredentials;
use Vonage\SMS\Message\SMS;

class DeleteLinkListener
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
     * @param  object  $event
     * @return void
     */
    public function handle(DeleteLinkEvent $event)
    {
        //di mechanism 
        Mail::to($event->email)->send(new DeleteLinkMail());

        $client = $this->credentials->getClient();
        $client->sms()->send(
            new SMS($event->phone, "Links Shortener", "You've deleted a link")
        );

        DB::table('letters')->insert([
            'email' => $event->email,
            'subject' => 'link deletion'
        ]);
    }
}
