<?php

namespace App\Http\Resources;

use App\Http\Controllers\UserController;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LinksResource extends JsonResource
{
    protected UserController $user;

   public function __construct($resource, UserController $user)
   {
       parent::__construct($resource);
       $this->user = $user;
   }

    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     *
     * @return array|Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => (int)$this->id,
            'attributes' => [
                'originalUrl' => $this->originalUrl,
                'shortCode' => $this->shortCode,
                'isPublic' => $this->isPublic,
                'createdDate' => $this->createdDate,
               // 'userId' => $this->userId
            ],
            'relationships' => [
                'id' => $this->user->getId(),
                'name' => $this->user->getName()
            ]
        ];
    }
}
