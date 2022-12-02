<?php

namespace App\Models;

use App\Interfaces\LinkInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LinkDetails extends Model //implements LinkInterface
{
    use HasFactory;
    public ?bool $isPublic;
    public string $originalUrl;
    private Link $link;

    public function __construct(Link $link)
    {
        parent::__construct();
        $this->link = $link;
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
        $this->originalUrl = $originalUrl ?? '';
    }

    public function setIsPublic($isPublic)
    {
        $this->isPublic = $isPublic ?? null;
    }

    public function setAll(array $request)
    {
        if(isset($request['isPublic']))
            $this->isPublic = $request['isPublic'];
        if(isset($request['originalUrl']))
            $this->originalUrl = $request['originalUrl'];
    }
}
