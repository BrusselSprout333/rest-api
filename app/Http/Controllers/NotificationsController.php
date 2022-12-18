<?php

namespace App\Http\Controllers;

use App\Interfaces\NotificationsServiceInterface;

class NotificationsController extends Controller
{
    public function __construct(
        protected NotificationsServiceInterface $notificationsService
    ) {}

    public function linkCreated($email, $phone, $originalLink)
    {
        $this->notificationsService->linkCreatedMail($email, $originalLink);
        $this->notificationsService->linkCreatedSMS($phone, $originalLink);
    }

    public function linkUpdated($email, $phone, $originalLink)
    {
        $this->notificationsService->linkUpdatedMail($email, $originalLink);
        $this->notificationsService->linkUpdatedSMS($phone, $originalLink);
    }

    public function linkDeleted($email, $phone, $originalLink)
    {
        $this->notificationsService->linkDeletedMail($email, $originalLink);
        $this->notificationsService->linkDeletedSMS($phone, $originalLink);
    }
}
