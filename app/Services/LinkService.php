<?php

namespace App\Services;

use App\Repositories\LinkRepository;
use Illuminate\Support\Facades\Validator;
use InvalidArgumentException;


class LinkService
{
    protected $linkRepository;

    public function __construct(LinkRepository $linkRepository)
    {
        $this->linkRepository = $linkRepository;
    }

    public function getAll()
    {
        return $this->linkRepository->getAll();
    }

    public function getAllByUser($userId)
    {
        return $this->linkRepository->getAllByUser($userId);
    }

    public function getById($id)
    {
        return $this->linkRepository->getById($id);
    }

    public function getByShortCode($shortCode)
    {
        return $this->linkRepository->getByShortCode($shortCode);
    }

    public function getOriginalLink($shortCode)
    {
        return $this->linkRepository->getOriginalLink($shortCode);
    }

    public function create($userId, $linkDetails)
    {
        return $this->linkRepository->create($userId, $linkDetails);
    }

    public function update($linkId, $shortCode, $linkDetails)
    {
//        $validator = Validator::make($data, [
//            'originalUrl' => 'bail|max:255|string',
//            'shortCode' => 'bail|string',
//            'isPublic' => 'bail|boolean'
//        ]);
//
//        if($validator->fails())
//            throw new InvalidArgumentException($validator->errors()->first());

        return $this->linkRepository->update($linkId, $shortCode, $linkDetails);
    }

    public function delete($id)
    {
        $this->linkRepository->delete($id);
    }
}


