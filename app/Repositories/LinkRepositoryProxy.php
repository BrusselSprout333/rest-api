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

    public function __construct(LinkRepository $repository)
    {
        $this->repository = $repository;
    }

    public function create(int $userId, LinkDetails $linkDetails) 
    {
        
    }

    public function update(int $linkId, ?string $shortCode, LinkDetails $linkDetails) 
    {
        
    }

    public function delete(int $linkId)
    {
    
    }

    public function getByShortCode(string $shortCode) 
    {
        
    }

    public function getById(int $linkId) 
    {
        $currentKey = 'link:id:';
        $hash = Redis::get($currentKey.$linkId);
        if (!isset($hash)) {
            $link = $this->repository->getById($linkId);
            Redis::set($currentKey.$linkId, $link);
        } else {
            $hash = Redis::get($currentKey.$linkId);
            $link = $this->ConvertHashToArray($hash);
        }
        return $link;
    }

    public function getOriginalLink(string $shortCode) 
    {
        $currentKey = 'originalUrl:shortCode:';
        $hash = Redis::get($currentKey.$shortCode);
        if (!isset($hash)) {
            $link = $this->repository->getOriginalLink($shortCode);
            Redis::set($currentKey.$shortCode, $link);
        } else {
            $hash = Redis::get($currentKey.$shortCode);
            $link = $this->ConvertHashToArray($hash);
        }
        return $link;
    }

    public function getAll()
    {
        $currentKey = 'links';
        $hash = Redis::get($currentKey);
        if (!isset($hash)) {
            $collection = $this->repository->getAll();
            Redis::set($currentKey, $collection);
        } else {
            $hash = Redis::get($currentKey);
            $collection = $this->ConvertHashToCollection($hash);
        }
        return $collection;
    }

    public function getAllByUser(int $userId)
    {
        $currentKey = 'links:user:';
        $hash = Redis::get($currentKey.$userId);
        if (!isset($hash)) {
            $collection = $this->repository->getAllByUser($userId);
            Redis::set($currentKey.$userId, $collection);
        } else {
            $hash = Redis::get($currentKey.$userId);
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