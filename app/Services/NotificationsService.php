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
    
    public function linkCreatedMail($email)
    {
        Mail::to($email)->send(new CreateLinkMail());

        DB::table('letters')->insert([
            'email' => $email,
            'subject' => 'link creation'
        ]);
    }

    public function linkCreatedSMS($phone)
    {
        $client = $this->credentials->getClient();
        // $client->sms()->send(
        //     new SMS($phone, "Links Shortener", "You've created a link")
        // );

        DB::table('letters')->insert([
            'phone' => $phone,
            'subject' => 'link creation'
        ]);
    }

    public function linkUpdatedMail($email)
    {
        Mail::to($email)->send(new UpdateLinkMail());

        DB::table('letters')->insert([
            'email' => $email,
            'subject' => 'link updation'
        ]);
    }

    public function linkUpdatedSMS($phone)
    {
        $client = $this->credentials->getClient();
        // $client->sms()->send(
        //     new SMS($phone, "Links Shortener", "You've updated a link")
        // );

        DB::table('letters')->insert([
            'phone' => $phone,
            'subject' => 'link updation'
        ]);
    }

    public function linkDeletedMail($email)
    {
        Mail::to($email)->send(new DeleteLinkMail());

        DB::table('letters')->insert([
            'email' => $email,
            'subject' => 'link deletion'
        ]);
    }

    public function linkDeletedSMS($phone)
    {
        $client = $this->credentials->getClient();
        // $client->sms()->send(
        //     new SMS($phone, "Links Shortener", "You've deleted a link")
        // );

        DB::table('letters')->insert([
            'phone' => $phone,
            'subject' => 'link deletion'
        ]);
    }
}