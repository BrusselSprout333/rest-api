<?php
declare(strict_types=1);

namespace App\Helpers\Utilites;

use App\Models\Link;

class ShortLinkGenerator
{
    public function __construct(private Link $link)
    {}

    public function generateShortLink(string $originalUrl): string
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

        // Проверяем в БД
        if($this->link->where('shortCode', $number)->first())
            throw new \Exception('Shortcode for this link was already created');

        return $number;
    }
}
