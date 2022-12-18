<?php

namespace App\Console\Commands;

use App\Events\UpdateLinkEvent;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Redis;

class UpdateLinkSubscribe extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'subscribe:updateLink';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Subscribe to a Redis channel of link updation';

    /**
     * Execute the console command.
     *
     */
    public function handle()
    {
        Redis::subscribe(['link_updated'], function ($data) {
            var_dump($data);
            $pieces = explode(",", $data);
            $email = $pieces[0];
            $phone = $pieces[1];
            $originalLink = $pieces[2];

            UpdateLinkEvent::dispatch($email, $phone, $originalLink);
        });
    }
}
