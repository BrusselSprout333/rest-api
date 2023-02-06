<?php
declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class LinkDetails extends Link
{
    use HasFactory;

    private ?bool $isPublic;
    private ?string $originalUrl;

    public function __construct(private Link $link)
    {
        parent::__construct();
        $this->link = $link;
    }

    public function getIsPublic(): ?bool
    {
        return $this->isPublic;
    }

    public function getOriginalUrl(): ?string
    {
        return $this->originalUrl;
    }

    public function setOriginalUrl(?string $originalUrl)
    {
        $this->originalUrl = $originalUrl ?? null;
    }

    public function setIsPublic(?bool $isPublic)
    {
        $this->isPublic = $isPublic ?? null;
    }
}
