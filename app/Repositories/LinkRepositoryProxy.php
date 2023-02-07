<?php
declare(strict_types=1);

namespace App\Repositories;

use App\Interfaces\LinkRepositoryInterface;
use App\Models\LinkDetails;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Collection;
use App\Models\Link;

class LinkRepositoryProxy implements LinkRepositoryInterface
{
    private $repository;

    const LINK_ID = 'link:id:';
    const LINKS = 'links';
    const LINKS_USER = 'links:user:';
    const LINK_SHORTCODE = 'link:shortCode:';
    const URL_SHORTCODE = 'originalUrl:shortCode:';


    public function __construct(LinkRepository $repository, protected Link $link)
    {
        $this->repository = $repository;
    }

    public function create(Link $link) 
    {
        $link = $this->repository->create($link);
        Redis::del(self::LINKS);
        Redis::del(self::LINKS_USER.$link->userId);

        Redis::set(self::LINK_ID.$link->id, $link);
        Redis::set(self::LINK_SHORTCODE.$link->shortCode, $link);
        Redis::set(self::URL_SHORTCODE.$link->shortCode, $link->originalUrl);

        return $link;
    }

    public function update(int $linkId, ?string $changedShortCode, LinkDetails $linkDetails) 
    {
        $link = $this->repository->getById($linkId);
        $userId = $link->userId;
        $shortCode = $link->shortCode;

        $link = $this->repository->update($linkId, $changedShortCode, $linkDetails);

        Redis::del(self::LINK_ID.$linkId);
        Redis::set(self::LINK_ID.$linkId, $link);

        Redis::del(self::LINK_SHORTCODE.$shortCode);
        Redis::set(self::LINK_SHORTCODE.$link->shortCode, $link);

        Redis::del(self::URL_SHORTCODE.$shortCode);
        Redis::set(self::URL_SHORTCODE.$link->shortCode, $link->url);

        Redis::del(self::LINKS);
        Redis::del(self::LINKS_USER.$userId);
        return $link;
    }

    public function delete(int $linkId)
    {
        $link = $this->repository->getById($linkId);
        $userId = $link->userId;
        $shortCode = $link->shortCode;

        $this->repository->delete($linkId);

        Redis::del(self::LINKS_USER.$userId);
        Redis::del(self::LINK_ID.$linkId);
        Redis::del(self::LINK_SHORTCODE.$shortCode);
        Redis::del(self::URL_SHORTCODE.$shortCode);
        Redis::del(self::LINKS);
    }

    public function getByShortCode(string $shortCode) 
    {
        $hash = Redis::get(self::LINK_SHORTCODE.$shortCode);
        if (!isset($hash)) {
            $link = $this->repository->getByShortCode($shortCode);
            Redis::set(self::LINK_SHORTCODE.$shortCode, $link);
        } else {
            $hash = Redis::get(self::LINK_SHORTCODE.$shortCode);
            $link = $this->ConvertHashForOneLink($hash);
        }
        return $link;
    }

    public function getById(int $linkId) 
    {
        $hash = Redis::get(self::LINK_ID.$linkId);
        if (!isset($hash)) {
            $link = $this->repository->getById($linkId);
            Redis::set(self::LINK_ID.$linkId, $link);
        } else {
            $hash = Redis::get(self::LINK_ID.$linkId);
            $link = $this->ConvertHashForOneLink($hash);
        }
        return $link;
    }

    public function getOriginalLink(string $shortCode) 
    {
        $hash = Redis::get(self::URL_SHORTCODE.$shortCode);
        if (!isset($hash)) {
            $link = $this->repository->getOriginalLink($shortCode);
            Redis::set(self::URL_SHORTCODE.$shortCode, $link);
        } else {
            $link = Redis::get(self::URL_SHORTCODE.$shortCode);
        }
        return $link;
    }

    public function getAll()
    {
        $hash = Redis::get(self::LINKS);
        if (!isset($hash)) {
            $collection = $this->repository->getAll();
            Redis::set(self::LINKS, $collection);
        } else {
            $hash = Redis::get(self::LINKS);
            $collection = $this->ConvertHashForCollection($hash);
        }
        return $collection;
    }

    public function getAllByUser(int $userId)
    {
        $hash = Redis::get(self::LINKS_USER.$userId);
        if (!isset($hash)) {
            $collection = $this->repository->getAllByUser($userId);
            Redis::set(self::LINKS_USER.$userId, $collection);
        } else {
            $hash = Redis::get(self::LINKS_USER.$userId);
            $collection = $this->ConvertHashForCollection($hash);
        }
        return $collection;
    }

    public function getByOriginalLink($url){
        return $this->repository->getByOriginalLink($url);
    }

    public function getIdByUrlAndUserId($url, $userId){
        return $this->repository->getIdByUrlAndUserId($url, $userId);
    }


    private function ConvertHashForCollection($hash)
    {
        $hash = str_replace("[", "", $hash);
        $hash = str_replace("{", "", $hash);
        $hash = stripcslashes($hash);
        $hash = str_replace("\"", "", $hash);
        $links = explode("}", $hash);
        array_pop($links);

        $i = 0;
        foreach ($links as $link) {
            $link = trim($link,',');
            $attributes[++$i] = explode(",", $link);
        }

        $array = [];
        $j = 0;
        foreach ($attributes as $attribute) {
            ++$j;
            foreach ($attribute as $part) {
                $key = strstr($part, ':', true);
                $value_colon = strstr($part, ':');
                $value = ltrim($value_colon, ':');
                $array[$j][$key] = $value;

                if (isset($array[$j]['id']))
                    $array[$j]['id'] = (int)$array[$j]['id'];
                if (isset($array[$j]['userId']))
                    $array[$j]['userId'] = (int)$array[$j]['userId'];
                if (isset($array[$j]['isPublic']))
                    $array[$j]['isPublic'] = (bool)$array[$j]['isPublic'];
            }
        }
        return $array;
    }

    private function ConvertHashForOneLink($hash)
    {
        $hash = str_replace("[", "", $hash);
        $hash = str_replace("{", "", $hash);
        $hash = str_replace("}", "", $hash);
        $hash = stripcslashes($hash);
        $hash = str_replace("\"", "", $hash);
        $array_without_keys = explode(",", $hash);

        $array = [];
        foreach ($array_without_keys as $elem) {
            $key = strstr($elem, ':', true);
            $value_colon = strstr($elem, ':');
            $value = ltrim($value_colon, ':');
            $array[$key] = $value;
        }

        if (isset($array['id']))
            $array['id'] = (int)$array['id'];
        if (isset($array['userId']))
            $array['userId'] = (int)$array['userId'];
        if (isset($array['isPublic']))
            $array['isPublic'] = (bool)$array['isPublic'];

        return $array;
    }
}