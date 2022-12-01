<?php

namespace App\Models;

use App\Interfaces\LinkInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Link extends Model// implements LinkInterface
{
    use HasFactory;
    public int $Id;
    public int $userId;
    public string $originalUrl;
    public string $shortCode;
    public bool $isPublic;
    public string $createdDate;

    protected $fillable = [
        'userId',
        'originalUrl',
        'shortCode',
        'isPublic',
        'createdDate',
    ];

    protected $primaryKey = 'id';

    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }
//
//    public function getUserId()
//    {
//        return $this->userId;
//    }
//
//    public function setUserId($userId)
//    {
//        $this->userId = $userId;
//    }
//
//
//    public function getOriginalUrl()
//    {
//        return $this->originalUrl;
//    }
//
//    public function setOriginalUrl($originalUrl)
//    {
//        $this->originalUrl = $originalUrl;
//    }
//
//
//    public function getShortCode($shortCode)
//    {
//        return $this->shortCode;
//    }
//
//    public function setShortCode($shortCode)
//    {
//        $this->shortCode = $shortCode;
//    }
//
//
//    public function getIsPublic()
//    {
//        return $this->isPublic;
//    }
//
//    public function setIsPublic($isPublic)
//    {
//        $this->isPublic = $isPublic;
//    }
//
//
//    public function getCreatedDate()
//    {
//        return $this->createdDate;
//    }
//
//    public function setCreatedDate($createdDate)
//    {
//        $this->userId = $createdDate;
//    }
}
