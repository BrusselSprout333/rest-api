<?php

namespace App\Listeners;

use App\Events\DeleteLinkEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;
use App\Mail\DeleteLinkMail;
use Illuminate\Support\Facades\DB;

class DeleteLinkListener
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
    public function handle(DeleteLinkEvent $event)
    {
        DB::table('letters')->insert([
            'email' => $event->email,
            'subject' => 'link deletion'
        ]);
        
        //di mechanism 
        Mail::to($event->email)->send(new DeleteLinkMail());
    }
}
