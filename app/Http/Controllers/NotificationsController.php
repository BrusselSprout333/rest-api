<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Interfaces\NotificationsServiceInterface;

class NotificationsController extends Controller
{
    //send notifications to 2 channels
    public function __construct(
        protected NotificationsServiceInterface $notificationsService
    ) {}
}
