<?php

namespace App\Interfaces;

use App\Models\LinkDetails;

interface LinkServiceInterface
{
    public function create(int $userId, LinkDetails $linkDetails, ?bool $recreate);
    public function update(int $linkId, ?string $shortCode, LinkDetails $linkDetails);
    public function delete(int $linkId);
    public function getOriginalLink(string $shortCode);
    public function getAllByUser(int $userId);
    public function getByShortCode(string $shortCode);
    public function getById(int $linkId);
    public function getAll();
}
