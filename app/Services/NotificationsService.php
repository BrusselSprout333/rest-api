<?php
declare(strict_types=1);

namespace App\Services;

use App\Events\DeleteLinkEvent;
use App\Events\UpdateLinkEvent;
use App\Interfaces\NotificationsServiceInterface;
use App\Http\Controllers\UserController;
use App\Events\CreateLinkEvent;
use Illuminate\Foundation\Events\Dispatchable;

class NotificationsService implements NotificationsServiceInterface
{
    public function __construct(
        protected UserController $user,
    ){}
    
    public function linkCreated()
    {
        //отправка по нескольким направлениям
        //di mechanism
        //interface

        CreateLinkEvent::dispatch($this->user->getEmail());
    }

    public function linkUpdated()
    {
        UpdateLinkEvent::dispatch($this->user->getEmail());
    }

    public function linkDeleted()
    {
        DeleteLinkEvent::dispatch($this->user->getEmail());
    }
}