<?php
declare(strict_types=1);

namespace App\Repositories;

use App\Interfaces\LinkRepositoryInterface;
use App\Models\LinkDetails;
use Illuminate\Support\Facades\Redis;

class LinkRepositoryProxy implements LinkRepositoryInterface
{
    private $repository;

    /**
     * @var string[]
     */
    private $cache = [];

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

    public function getById(int $linkId) 
    {
        
    }

    public function getOriginalLink(string $shortCode) 
    {

    }

    public function getByShortCode(string $shortCode) 
    {
        
    }

    public function getAll()
    {

    }

    public function getAllByUser(int $userId)
    {
        $hash = Redis::get('links:user:'.$userId);
        if (!isset($hash)) {
            echo "CacheProxy MISS. ";
            $col = $this->repository->getAllByUser($userId);
            Redis::set('links:user:'.$userId, $col);
            return $col;
        } else {
            echo "CacheProxy HIT. Retrieving result from cache.\n";
            $bodytag = Redis::get('links:user:'.$userId);
            $bodytag = str_replace("[", "", $bodytag);
            $bodytag = str_replace("{", "", $bodytag);
            $pieces = explode("}", $bodytag);
            array_pop($pieces);
            $i = 0;
            foreach ($pieces as $piece) {
                $i++;
                $piece = trim($piece,',');
                $part[$i] = explode(",", $piece);
            }

            $col = collect($part);
            return $col;
        }


        // if (!isset($this->cache[$userId])) {
        //     echo "CacheProxy MISS. ";
        //     $result = $this->repository->getAllByUser($userId);
        //     $this->cache[$userId] = $result;
        // } else {
        //     echo "CacheProxy HIT. Retrieving result from cache.\n";
        // }
        // return $this->cache[$userId];

            
        // $result = $this->repository->getAllByUser($userId);//return $result;
        // Redis::set('links:user:'.$userId, $result);
        // $bodytag = Redis::get('links:user:'.$userId);
        // $bodytag = str_replace("[", "", $bodytag);
        // $bodytag = str_replace("{", "", $bodytag);
        // $pieces = explode("}", $bodytag);
        // array_pop($pieces);
        // $i = 0;
        // foreach ($pieces as $piece) {
        //     $i++;
        //     $piece = trim($piece,',');
        //     $part[$i] = explode(",", $piece);
        // }

        // $col = collect($part);
        // return $col;


        
        //Redis::set('link_by_user5', $result);
        //return Redis::get('link_by_user5');

        //if (!isset($this->cache)) {
        //     echo "CacheProxy MISS. ";
        //     $result = $this->repository->getAllByUser($userId);
        //     Redis::set('link_by_user' . $userId, $result);
        //     return $result;
        // } else {
        //     echo "CacheProxy HIT. Retrieving result from cache.\n";
        //     return $this->cache;
        // }
    }


    private function checkAccessByLinkId($linkId)
    {
        
    }

    private function checkAccessByShortCode($shortCode)
    {
        
    }


    private function ConvertHashToCollection($hash) //: Collection
    {
//
    }
}