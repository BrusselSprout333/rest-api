<?php
declare(strict_types=1);

namespace App\Services;

use App\Helpers\Utilites\SmsMessage;
use App\Interfaces\NotificationsServiceInterface;
use App\Http\Controllers\UserController;
use App\Mail\DeleteLinkMail;
use App\Mail\UpdateLinkMail;
use Illuminate\Support\Facades\Mail;
use App\Mail\CreateLinkMail;
use App\Helpers\Utilites\SmsCredentials;
use Vonage\SMS\Message\SMS;

class NotificationsService implements NotificationsServiceInterface
{
    const siteName = "Links Shortener";
    public function __construct(
        protected UserController $user,
        protected SmsCredentials $credentials,
        protected SmsMessage $message
    ){}
    
    public function linkCreatedMail($email, $originalLink)
    {
        Mail::to($email)->send(new CreateLinkMail($originalLink));
    }

    public function linkCreatedSMS($phone, $originalLink)
    {
        $client = $this->credentials->getClient();
        $client->sms()->send(
            new SMS($phone, $this::siteName, $this->message->messageCreate($originalLink))
        );
    }

    public function linkUpdatedMail($email, $originalLink)
    {
        Mail::to($email)->send(new UpdateLinkMail($originalLink));
    }

    public function linkUpdatedSMS($phone, $originalLink)
    {
        $client = $this->credentials->getClient();
        $client->sms()->send(
            new SMS($phone, $this::siteName, $this->message->messageUpdate($originalLink))
        );
    }

    public function linkDeletedMail($email, $originalLink)
    {
        Mail::to($email)->send(new DeleteLinkMail($originalLink));
    }

    public function linkDeletedSMS($phone, $originalLink)
    {
        $client = $this->credentials->getClient();
        $client->sms()->send(
            new SMS($phone, $this::siteName, $this->message->messageDelete($originalLink))
        );
    }
}