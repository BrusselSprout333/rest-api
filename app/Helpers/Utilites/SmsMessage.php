<?php
declare(strict_types=1);

namespace App\Helpers\Utilites;

class SmsMessage
{
    public function messageCreate($originalLink)
    {
        return "You've created a link: ".$originalLink;
    }

    public function messageUpdate($originalLink)
    {
        return "You've updated a link: ".$originalLink;
    }

    public function messageDelete($originalLink)
    {
        return "You've deleted a link: ".$originalLink;
    }
}
