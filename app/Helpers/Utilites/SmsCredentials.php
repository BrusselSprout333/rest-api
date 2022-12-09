<?php
declare(strict_types=1);

namespace App\Helpers\Utilites;

use Vonage\Client\Credentials\Basic;
use Vonage\Client;

class SmsCredentials
{
   // private Basic $basic;
    private Client $client;

    private static ?SmsCredentials $instance = null;

    private function __construct()
    {
       // $this->basic  = new Basic("6aafac1c", "1hb0pSZbiK3itNxh");
        $this->client = new Client(
            new Basic("6aafac1c", "1hb0pSZbiK3itNxh"), [
            'base_api_url' => 'https://LinkShortener.com'
        ]);
    }

    public static function getInstance(): ?SmsCredentials
    {
        if (self::$instance == null) {
            self::$instance = new SmsCredentials();
        }
        return self::$instance;
    }

    public function getClient()
    {
        return $this->client;
    }
}