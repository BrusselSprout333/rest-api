<?php

namespace App\Repositories;

use App\Http\Resources\LinksResource;
use App\Models\Link;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class LinkRepository
{
    protected $link;

    public function __construct(Link $link)
    {
        $this->link = $link;
    }

    public function create($data)//$userId, LinkDetails $linkDetails) : Link
    {
        $link = Link::create([
            'userId' => Auth::user()->id,
            'originalUrl' => $data['originalUrl'],
            'shortCode' => $this->createShortCode(),//->unique,
            'isPublic' => $data['isPublic'],
            'createdDate' => date("y-m-d")
        ]);
        return new LinksResource($link);
    }
//
//    public function update($linkId, string $shortCode, LinkDetails $linkDetails) : Link
//    {
//
//    }
//
//    public function delete($linkId) : void
//    {
//
//    }
//
    public function getById($linkId) //: Link
    {
        $userId = $this->link->where('id', $linkId)->get('userId');

        if(Auth::user()->id === $userId[0]['userId'])
            return $this->link
                ->where('id', $linkId)
                ->get();
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

//        return LinksResource::collection(
//            Link::where('userId', Auth::user()->id)->get()
//        );
    }

//    public function getAllByUser($userId) : Collection
//    {
//
//    }

    private function createShortCode()
    {
        $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyz';
        return substr(str_shuffle($permitted_chars), 0, 8);
    }
}

