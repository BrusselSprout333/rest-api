<?php

namespace App\Listeners;

use App\Events\UpdateLinkEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;
use App\Mail\UpdateLinkMail;
use Illuminate\Support\Facades\DB;

class UpdateLinkListener
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
     * @param  \App\Events\UpdateLinkEvent  $event
     * @return void
     */
    public function handle(UpdateLinkEvent $event)
    {
        DB::table('letters')->insert([
            'email' => $event->email,
            'subject' => 'link updation'
        ]);
        
        //di mechanism
        Mail::to($event->email)->send(new UpdateLinkMail());
    }
}
