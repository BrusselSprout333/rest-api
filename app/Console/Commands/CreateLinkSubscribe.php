<?php

namespace App\Console\Commands;

use App\Events\CreateLinkEvent;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Redis;

class CreateLinkSubscribe extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'subscribe:createLink';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Subscribe to a Redis channel of link creation';

    /**
     * Execute the console command.
     *
     */
    public function handle()
    {
        Redis::subscribe(['link_created'], function ($data) {
            var_dump($data);
            $pieces = explode(",", $data);
            $email = $pieces[0];
            $phone = $pieces[1];
            $originalLink = $pieces[2];

            CreateLinkEvent::dispatch($email, $phone, $originalLink);
        });
    }
}
