<?php
declare(strict_types=1);

namespace App\Listeners;

use App\Events\CreateLinkEvent;
use Illuminate\Support\Facades\Mail;
use App\Mail\CreateLinkMail;
use Illuminate\Support\Facades\DB;
use App\Helpers\Utilites\SmsCredentials;
use Vonage\SMS\Message\SMS;

class CreateLinkListener
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
    public function handle(CreateLinkEvent $event)
    {  
        //di mechanism
        Mail::to($event->email)->send(new CreateLinkMail());

        $client = $this->credentials->getClient();
        $client->sms()->send(
            new SMS($event->phone, "Links Shortener", "You've created a link")
        );

        DB::table('letters')->insert([
            'email' => $event->email,
            'subject' => 'link creation'
        ]);
    }
}
