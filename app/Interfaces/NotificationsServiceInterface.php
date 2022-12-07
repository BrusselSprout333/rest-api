<?php

namespace App\Interfaces;

interface NotificationsServiceInterface
{
    public function linkCreated();

    public function linkUpdated();

    public function linkDeleted();
}
