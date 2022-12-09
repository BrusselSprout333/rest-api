<?php

namespace App\Interfaces;

interface NotificationsServiceInterface
{
    public function linkCreatedMail($email);
    public function linkCreatedSMS($phone);

    public function linkUpdatedMail($email);
    public function linkUpdatedSMS($phone);

    public function linkDeletedMail($email);
    public function linkDeletedSMS($phone);
}
