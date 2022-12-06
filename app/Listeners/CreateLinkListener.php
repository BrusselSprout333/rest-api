<?php

namespace App\Listeners;

use App\Events\CreateLinkEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;
use App\Mail\CreateLinkMail;
use Illuminate\Support\Facades\DB;

class CreateLinkListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(CreateLinkEvent $event)
    {
        DB::table('letters')->insert([
                'email' => $event->email
            ]);
            
        Mail::to($event->email)->send(new CreateLinkMail());
    }
}
