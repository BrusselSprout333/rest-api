<?php

namespace App\Interfaces;

use App\Models\LinkDetails;
use App\Models\Link;

interface LinkRepositoryInterface
{
    public function create(Link $link);
    public function update(int $linkId, ?string $shortCode, LinkDetails $linkDetails);
    public function delete(int $linkId);
    public function getOriginalLink(string $shortCode);
    public function getAllByUser(int $userId);
    public function getByShortCode(string $shortCode);
    public function getById(int $linkId);
    public function getAll();
    public function getByOriginalLink($url);
    public function getIdByUrlAndUserId($url, $userId);
}

