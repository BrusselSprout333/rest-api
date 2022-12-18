<?php

namespace App\Console\Commands;

use App\Events\DeleteLinkEvent;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Redis;

class DeleteLinkSubscribe extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'subscribe:deleteLink';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Subscribe to a Redis channel of link deletion';

    /**
     * Execute the console command.
     *
     */
    public function handle()
    {
        Redis::subscribe(['link_deleted'], function ($data) {
            var_dump($data);
            $pieces = explode(",", $data);
            $email = $pieces[0];
            $phone = $pieces[1];
            $originalLink = $pieces[2];

            DeleteLinkEvent::dispatch($email, $phone, $originalLink);
        });
    }
}
