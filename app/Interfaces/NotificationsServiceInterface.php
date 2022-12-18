<?php

namespace App\Interfaces;

interface NotificationsServiceInterface
{
    public function linkCreatedMail($email, $originalLink);
    public function linkCreatedSMS($phone, $originalLink);

    public function linkUpdatedMail($email, $originalLink);
    public function linkUpdatedSMS($phone, $originalLink);

    public function linkDeletedMail($email, $originalLink);
    public function linkDeletedSMS($phone, $originalLink);
}
