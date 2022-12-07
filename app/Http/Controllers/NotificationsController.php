<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Interfaces\NotificationsServiceInterface;

class NotificationsController extends Controller
{
    public function __construct(
        protected NotificationsServiceInterface $notificationsService
    ) {}

    public function linkCreated()
    {
        $this->notificationsService->linkCreated();
    }

    public function linkUpdated()
    {
        $this->notificationsService->linkUpdated();
    }

    public function linkDeleted()
    {
        $this->notificationsService->linkDeleted();
    }
}
