<?php

namespace App\Interfaces;

interface LinkInterface {
    public function getOriginalUrl();
    public function getIsPublic();
    public function setIsPublic($isPublic);
    public function setOriginalUrl($originalUrl);
}
