<?php
declare(strict_types=1);

namespace App\Repositories;

use App\Helpers\Utilites\ShortLinkGenerator;
use App\Http\Controllers\UserController;
use App\Interfaces\LinkRepositoryInterface;
use App\Models\Link;
use App\Models\LinkDetails;
use Exception;
use Illuminate\Support\Collection;


class LinkRepository implements LinkRepositoryInterface
{

    public function __construct(protected Link $link, protected UserController $user, private ShortLinkGenerator $shortLink)
    {
    }

    public function create(int $userId, LinkDetails $linkDetails) : Link
    {
        $this->link->setUserId($userId);
        $this->link->setIsPublic($linkDetails->getIsPublic());
        $this->link->setShortCode($this->shortLink->generateShortLink($linkDetails->getOriginalUrl()));
        $this->link->setOriginalUrl($linkDetails->getOriginalUrl());
        $this->link->setCreatedDate(date("y-m-d"));

        $this->link->save();

        return $this->link;
    }

    /**
     * @throws Exception
     */
    public function update(int $linkId, ?string $shortCode, LinkDetails $linkDetails) : Link
    {
        if($this->link->find($linkId)) {
            if($this->checkAccessByLinkId($linkId)) {
                $link = $this->link->find($linkId);

                if($this->link->where('shortCode', $shortCode)->first())
                    throw new Exception('This shortCode already exists');
                $link->update([
                    'shortCode' => $shortCode ?? $link->getShortCode(),
                    'isPublic' => $linkDetails->getIsPublic() ?? $link->getIsPublic(),
                    'originalUrl' => $linkDetails->getOriginalUrl() ?? $link->getOriginalUrl(),
                ]);
                return $link;
            }
            else throw new Exception('you dont have access');
        } else throw new Exception('this link doesnt exist');
    }

    /**
     * @throws Exception
     */
    public function delete(int $linkId) : void
    {
        if($this->link->find($linkId)) {
            if ($this->checkAccessByLinkId($linkId)) {
                $link = $this->link->find($linkId);
                $link->delete();
            }
            else throw new Exception('you dont have access');
        } else throw new Exception('this link doesnt exist');
    }

    /**
     * @throws Exception
     */
    public function getById(int $linkId) : Link
    {
        if($this->link->find($linkId)) {
            if($this->checkAccessByLinkId($linkId)) {
                return $this->link->find($linkId);
            }
            else throw new Exception('you dont have access');
        } else throw new Exception('this link doesnt exist');
    }

    /**
     * @throws Exception
     */
    public function getOriginalLink(string $shortCode) : Link
    {
        if($this->link->where('shortCode', $shortCode)->get('userId')->first()) {
            if ($this->checkAccessByShortCode($shortCode)) {
                return $this->link->where('shortCode', $shortCode)
                        ->get('originalUrl')->first();

            } else throw new Exception('you dont have access');
        } else throw new Exception('this link doesnt exist');
    }

    /**
     * @throws Exception
     */
    public function getByShortCode(string $shortCode) : Link
    {
        if($this->link->where('shortCode', $shortCode)->get('userId')->first()) {
            if ($this->checkAccessByShortCode($shortCode)) {
                return $this->link->where('shortCode', $shortCode)->first();

            } else throw new Exception('you dont have access');
        } else throw new Exception('this link doesnt exist');
    }

    public function getAll() : Collection
    {
        if($this->user->isAuthenticated()) {
            return $this->link->where('isPublic', true)->get();//->paginate(10);
        } else throw new Exception('you dont have access');
    }

    public function getAllByUser(int $userId) : Collection
    {
        if($this->user->getId() === $userId) {
            return $this->link->where('userId', $userId)->get();
        } else throw new Exception('you dont have access');
    }


    private function checkAccessByLinkId($linkId): bool
    {
        if($this->link->where('id', $linkId)->get('isPublic')->first()['isPublic'])
            return true;
        $userId = $this->link->where('id', $linkId)->get('userId')->first();
        return $this->user->getId() === $userId['userId'];
    }

    private function checkAccessByShortCode($shortCode): bool
    {
        if($this->link->where('shortCode', $shortCode)->get('isPublic')->first()['isPublic'])
            return true;
        $userId = $this->link->where('shortCode', $shortCode)->get('userId')->first();
        return $this->user->getId() === $userId['userId'];
    }
}

