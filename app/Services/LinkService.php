<?php

namespace App\Services;

use App\Http\Resources\LinksResource;
use App\Models\Link;
use App\Repositories\LinkRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use InvalidArgumentException;


class LinkService
{
    protected $linkRepository;

    public function __construct(LinkRepository $linkRepository)
    {
        $this->linkRepository = $linkRepository;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function getAll()
    {
        return $this->linkRepository->getAll();
    }

    public function create($data)
    {
        $validator = Validator::make($data, [
            'originalUrl' => 'required|max:255|string',
            'isPublic' => 'required|boolean'
        ]);

        if($validator->fails())
            throw new InvalidArgumentException($validator->errors()->first());

        return $this->linkRepository->create($data);
    }

    public function getById($id)
    {
        return $this->linkRepository->getById($id);
    }
}


