<?php
declare(strict_types=1);

namespace App\Repositories;

use App\Http\Controllers\UserController;
use App\Models\Link;
use App\Models\LinkDetails;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class LinkRepository
{
    protected Link $link;
    protected UserController $user;

    public function __construct(Link $link, UserController $user)
    {
        $this->link = $link;
        $this->user = $user;
    }

    public function create(int $userId, LinkDetails $linkDetails) : Link
    {
        $this->link->setUserId($userId);
        $this->link->setIsPublic($linkDetails->getIsPublic());
        $this->link->setShortCode($this->createShortCode());
        $this->link->setOriginalUrl($linkDetails->getOriginalUrl());
        $this->link->setCreatedDate(date("y-m-d"));
        $this->link->save();
        return $this->link;
//        $this->link->originalUrl = $linkDetails->originalUrl;
//        $this->link->isPublic = $linkDetails->isPublic;
//        $this->link->userId = $userId;//$this->user->getId();
//        $this->link->shortCode = $this->createShortCode();
//        $this->link->createdDate = date("y-m-d");


//        return Link::create([
//            'userId' => $userId,
//            'originalUrl' => $linkDetails->originalUrl,
//            'shortCode' => $this->createShortCode(),//->unique,
//            'isPublic' => $linkDetails->isPublic,
//            'createdDate' => date("y-m-d")
//        ]);
    }

    public function update(int $linkId, string $shortCode, LinkDetails $linkDetails) : Link
    {
        if($this->equalUserId($linkId)) {
            $link = $this->link->find($linkId);

            $link->update([
                'shortCode' => $shortCode ?? $link->getShortCode(),
                'isPublic' => $linkDetails->getIsPublic() ?? $link->getIsPublic(),
                'originalUrl' => $linkDetails->getOriginalUrl() ?? $link->getOriginalUrl(),
            ]);
            return $link;
        }
        else throw new \Exception('you dont have access');
    }

    public function delete($linkId) : void
    {
        if($this->equalUserId($linkId)) {
            $link = $this->link->find($linkId);
            if(isset($link))
                $link->delete();
            else throw new \Exception('this link doesnt exist');
        }
        else throw new \Exception('you dont have access');
    }

    public function getById($linkId) //: Link
    {
        //$userId = $this->link->where('id', $linkId)->get('userId');
        $link = $this->link->find($linkId);
        $data = [$link->getUserId(), $link->getOriginalUrl()];
        return $data;
//        if($this->equalUserId($linkId)) {
//            return $this->link->find($linkId);
//        }
//        else throw new \Exception('you dont have access');
    }

    public function getOriginalLink($shortCode) : Link
    {
        return $this->link->where('shortCode', $shortCode)->get('originalUrl')->first();
    }

    public function getByShortCode(string $shortCode) : Link
    {
        return $this->link->where('shortCode', $shortCode)->first();
    }

    public function getAll() : Collection
    {
        return $this->link->get();
    }

    public function getAllByUser(int $userId) : Collection
    {
        return $this->link->where('userId', $userId)->get();
    }

    private function createShortCode()
    {
        $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyz';
        return substr(str_shuffle($permitted_chars), 0, 8);
    }

    private function equalUserId($linkId): bool
    {
        $userId = $this->link->where('id', $linkId)->get('userId');

        return $this->user->getId() === $userId[0]['userId'];
    }
}

