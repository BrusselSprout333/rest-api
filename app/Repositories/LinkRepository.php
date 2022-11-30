<?php

namespace App\Repositories;

use App\Models\Link;
use Illuminate\Support\Collection;

class LinkRepository
{
    protected $link;

    public function __construct(Link $link)
    {
        $this->link = $link;
    }

    public function create($userId, LinkDetails $linkDetails) : Link
    {

    }

    public function update($linkId, string $shortCode, LinkDetails $linkDetails) : Link
    {

    }

    public function delete($linkId) : void
    {

    }

    public function getById($linkId) : Link
    {

    }

    public function getByShortCode($shortCode) : Link
    {

    }

    public function getAll($linkId) : Collection
    {

    }

    public function getAllByUser($userId) : Collection
    {

    }
}

