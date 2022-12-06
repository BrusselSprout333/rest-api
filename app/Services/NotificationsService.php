<?php
declare(strict_types=1);

namespace App\Services;

use App\Interfaces\NotificationsServiceInterface;
use App\Http\Controllers\UserController;
use App\Events\CreateLinkEvent;

class NotificationsService implements NotificationsServiceInterface
{

    public function __construct(
        protected UserController $user,
    ){}
    
    public function send()
    {
        event(new CreateLinkEvent($this->user->getEmail()));
    }
}