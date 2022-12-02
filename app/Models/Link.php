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

    public function setUserId($userId)
    {
        $this->userId = $userId;
    }


    public function getOriginalUrl()
    {
        return $this->originalUrl;
    }

    public function setOriginalUrl($originalUrl)
    {
        $this->originalUrl = $originalUrl;
    }


    public function getShortCode()
    {
        return $this->shortCode;
    }

    public function setShortCode($shortCode)
    {
        $this->shortCode = $shortCode;
    }


    public function getIsPublic()
    {
        return $this->isPublic;
    }

    public function setIsPublic($isPublic)
    {
        $this->isPublic = $isPublic;
    }


    public function getCreatedDate()
    {
        return $this->createdDate;
    }

    public function setCreatedDate($createdDate)
    {
        $this->createdDate = $createdDate;
    }
}
