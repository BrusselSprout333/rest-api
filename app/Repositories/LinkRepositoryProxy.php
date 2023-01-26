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

    public function create(int $userId, LinkDetails $linkDetails) 
    {
        $link = $this->repository->create($userId, $linkDetails);
        //Redis::set('link:id:'.$id, $link);
        //Redis::set('link:shortCode:'.$shortCode, $link);
        Redis::del(self::LINKS);
        Redis::del(self::LINKS_USER.$userId);
        return $link;
    }

    public function update(int $linkId, ?string $changedShortCode, LinkDetails $linkDetails) 
    {
        $userId = $this->link->where('id', $linkId)->get('userId')->first();
        $shortCode = $this->link->where('id', $linkId)->get('shortCode')->first();
        $link = $this->repository->update($linkId, $changedShortCode, $linkDetails);
        //сразу устанавливаем новый кэш
        Redis::del(self::LINK_ID.$linkId);
        Redis::set(self::LINK_ID, $link);
        Redis::del(self::LINK_SHORTCODE.$shortCode);
        Redis::set(self::LINK_SHORTCODE.$shortCode, $link);
        //удаление всех упоминаний неизмененной ссылки
        Redis::del(self::LINKS);
        Redis::del(self::LINKS_USER.$userId);
        Redis::del(self::URL_SHORTCODE.$shortCode);
        return $link;
    }

    public function delete(int $linkId)
    {
        $userId = $this->link->where('id', $linkId)->get('userId')->first();
        $shortCode = $this->link->where('id', $linkId)->get('shortCode')->first();
        $this->repository->delete($linkId);
        Redis::del(self::LINKS_USER.$userId); //удаление всех упоминаний ссылки
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
            $link = $this->ConvertHashToArray($hash);
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
            $link = $this->ConvertHashToArray($hash);
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
            $hash = Redis::get(self::URL_SHORTCODE.$shortCode);
            $link = $this->ConvertHashToArray($hash);
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
            $collection = $this->ConvertHashToCollection($hash);
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
            $collection = $this->ConvertHashToCollection($hash);
        }
        return $collection;
    }

    private function ConvertHashToCollection($hash) : Collection
    {
        $hash = str_replace("[", "", $hash);
        $hash = str_replace("{", "", $hash);
        $hash = stripcslashes($hash);
        $pieces = explode("}", $hash);
        array_pop($pieces);
        $i = 0;
        foreach ($pieces as $piece) {
            $piece = trim($piece,',');
            $parts[++$i] = explode(",", $piece);
        }
        return collect($parts);
    }

    private function ConvertHashToArray($hash)
    {
        $hash = str_replace("[", "", $hash);
        $hash = str_replace("{", "", $hash);
        $hash = str_replace("}", "", $hash);
        $hash = stripcslashes($hash);
        $link = explode(",", $hash);
        
        return $link;
    }
}