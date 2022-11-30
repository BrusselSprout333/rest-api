<?php

namespace App\Repositories;

use App\Http\Controllers\UserController;
use App\Http\Resources\LinksResource;
use App\Models\Link;
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

    public function create($data)//$userId, LinkDetails $linkDetails) : Link
    {
        return Link::create([
            'userId' => $this->user->getId(),
            'originalUrl' => $data['originalUrl'],
            'shortCode' => $this->createShortCode(),//->unique,
            'isPublic' => $data['isPublic'],
            'createdDate' => date("y-m-d")
        ]);
    }

    public function update($data, $linkId)//$linkId, string $shortCode, LinkDetails $linkDetails) : Link
    {
        if($this->equalUserId($linkId)) {
            $link = $this->link->find($linkId);
            $link->update($data);
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

    public function getById($linkId) : Link
    {
        if($this->equalUserId($linkId)) {
            return $this->link->find($linkId);
        }
        else throw new \Exception('you dont have access');
    }

//
//    public function getByShortCode($shortCode) : Link
//    {
//
//    }

    public function getAll() : Collection
    {
        return $this->link->get();
    }

//    public function getAllByUser($userId) : Collection
//    {
//          return LinksResource::collection(
//            Link::where('userId', Auth::user()->id)->get()
//        );
//    }

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

