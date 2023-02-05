<?php
declare(strict_types=1);

namespace App\Helpers\Utilites;

use App\Models\Link;

class ShortLinkGenerator
{
    public function __construct(private Link $link)
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

        //$new = new ShortLinkGenerator(new Link);
        //if ($new->db_search($number))
        //throw new \Exception('Shortcode for this link was already created');
       // $q = $this->link->where('shortCode', $number)->first();

        return $number; //$number;
        //$b = new NewClass(new Link);
        //return self::db_search($number);
        //if (self::db_search($number))
        // throw new \Exception('Shortcode for this link was already created');
        // Проверяем в БД
        //$link = new Link();
        // if ($this->link->findOrFail(1))
        //     return 2;

        // if ($this->link->where('shortCode', $number)->first())
        // {
        //     throw new \Exception('Shortcode for this link was already created');
        // }

        // $q = $this->link->where('shortCode', $number);
        // print_r(gettype($q));

        //return $number;
    }
}



