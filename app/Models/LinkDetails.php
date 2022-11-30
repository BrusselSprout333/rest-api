<?php

namespace App\Models;

use App\Interfaces\LinkInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LinkDetails extends Model implements LinkInterface
{
    use HasFactory;
    private bool $isPublic;
    private string $originalUrl;
    private Link $link;

    public function __construct(Link $link)
    {
        parent::__construct();
        $this->link = $link;
        $this->isPublic = $link->getIsPublic();
        $this->originalUrl = $link->getOriginalUrl();
    }

    public function getIsPublic()
    {
        return $this->isPublic;
    }

    public function getOriginalUrl()
    {
        return $this->originalUrl;
    }

    public function setOriginalUrl($originalUrl)
    {
        $this->originalUrl = $originalUrl;
    }

    public function setIsPublic($isPublic)
    {
        $this->isPublic = $isPublic;
    }
}
