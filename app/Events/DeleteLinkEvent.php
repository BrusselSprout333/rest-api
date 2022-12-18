<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class DeleteLinkEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public string $email;
    public string $phone;
    public string $originalLink;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($email, $phone, $originalLink)
    {
        $this->email = $email;
        $this->phone = $phone;
        $this->originalLink = $originalLink;
    }
}
