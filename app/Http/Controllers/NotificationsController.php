<?php

namespace App\Http\Controllers;

use App\Interfaces\NotificationsServiceInterface;

class NotificationsController extends Controller
{
    public function __construct(
        protected NotificationsServiceInterface $notificationsService
    ) {}

    public function linkCreated($email, $phone)
    {
        $this->notificationsService->linkCreatedMail($email);
        $this->notificationsService->linkCreatedSMS($phone);
    }

    public function linkUpdated($email, $phone)
    {
        $this->notificationsService->linkUpdatedMail($email);
        $this->notificationsService->linkUpdatedSMS($phone);
    }

    public function linkDeleted($email, $phone)
    {
        $this->notificationsService->linkDeletedMail($email);
        $this->notificationsService->linkDeletedSMS($phone);
    }
}
