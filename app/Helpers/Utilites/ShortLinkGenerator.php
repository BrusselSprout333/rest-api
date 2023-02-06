<?php
declare(strict_types=1);

namespace App\Helpers\Utilites;

use App\Interfaces\LinkRepositoryInterface;
use App\Models\Link;

class ShortLinkGenerator
{

    public function __construct(protected LinkRepositoryInterface $linkRepository)
    {
    }

    public function generateShortLink(string $originalUrl, int $userId) : string
    {
        // Генерируем код

        //обрезка схемы
        $number = stristr($originalUrl, '://');
        //замена символов
        $number = str_replace('-', '_', $number);
        $number = str_replace('/', '-', $number);
        //обрезка
        $number = substr($number, 3);
        $number = substr($number, 0, 15);
        $number .= (string)$userId;

        if ($this->linkRepository->getByShortCode($number))
        {
            throw new \Exception('Shortcode for this link was already created');
        }

        return $number; 
    }
}



