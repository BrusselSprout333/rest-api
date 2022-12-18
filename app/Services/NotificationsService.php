<?php
declare(strict_types=1);

namespace App\Services;

use App\Interfaces\NotificationsServiceInterface;
use App\Http\Controllers\UserController;
use App\Mail\DeleteLinkMail;
use App\Mail\UpdateLinkMail;
use Illuminate\Support\Facades\Mail;
use App\Mail\CreateLinkMail;
use Illuminate\Support\Facades\DB;
use App\Helpers\Utilites\SmsCredentials;
use Vonage\SMS\Message\SMS;

class NotificationsService implements NotificationsServiceInterface
{
    public function __construct(
        protected UserController $user,
        private SmsCredentials $credentials
    ){}
    
    public function linkCreatedMail($email, $originalLink)
    {
        Mail::to($email)->send(new CreateLinkMail($originalLink));

        DB::table('letters')->insert([
            'email' => $email,
            'subject' => 'link creation'
        ]);
    }

    public function linkCreatedSMS($phone, $originalLink)
    {
        $client = $this->credentials->getClient();
        // $client->sms()->send(
        //     new SMS($phone, "Links Shortener", "You've created a link: ".$originalLink)
        // );

        DB::table('letters')->insert([
            'phone' => $phone,
            'subject' => 'link creation'
        ]);
    }

    public function linkUpdatedMail($email, $originalLink)
    {
        Mail::to($email)->send(new UpdateLinkMail($originalLink));

        DB::table('letters')->insert([
            'email' => $email,
            'subject' => 'link updation'
        ]);
    }

    public function linkUpdatedSMS($phone, $originalLink)
    {
        $client = $this->credentials->getClient();
        // $client->sms()->send(
        //     new SMS($phone, "Links Shortener", "You've updated a link: ".$originalLink)
        // );

        DB::table('letters')->insert([
            'phone' => $phone,
            'subject' => 'link updation'
        ]);
    }

    public function linkDeletedMail($email, $originalLink)
    {
        Mail::to($email)->send(new DeleteLinkMail($originalLink));

        DB::table('letters')->insert([
            'email' => $email,
            'subject' => 'link deletion'
        ]);
    }

    public function linkDeletedSMS($phone, $originalLink)
    {
        $client = $this->credentials->getClient();
        // $client->sms()->send(
        //     new SMS($phone, "Links Shortener", "You've deleted a link: ".$originalLink)
        // );

        DB::table('letters')->insert([
            'phone' => $phone,
            'subject' => 'link deletion'
        ]);
    }
}