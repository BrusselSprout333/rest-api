<?php
declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Link extends Model
{
    use HasFactory;

    protected $fillable = [
        'userId',
        'originalUrl',
        'shortCode',
        'isPublic',
        'createdDate',
    ];

    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    public function getUserId()
    {
        return $this->userId;
    }

    public function setUserId(int $userId)
    {
        $this->userId = $userId;
    }


    public function getOriginalUrl()
    {
        return $this->originalUrl;
    }

    public function setOriginalUrl(string $originalUrl)
    {
        $this->originalUrl = $originalUrl;
    }


    public function getShortCode()
    {
        return $this->shortCode;
    }

    public function setShortCode(string $shortCode)
    {
        $this->shortCode = $shortCode;
    }


    public function getIsPublic()
    {
        return $this->isPublic;
    }

    public function setIsPublic(bool $isPublic)
    {
        $this->isPublic = $isPublic;
    }


    public function getCreatedDate()
    {
        return $this->createdDate;
    }

    public function setCreatedDate(string $createdDate)
    {
        $this->createdDate = $createdDate;
    }
}
