<?php
declare(strict_types=1);

namespace App\Repositories;

use App\Helpers\Utilites\ShortLinkGenerator;
use App\Http\Controllers\UserController;
use App\Interfaces\LinkRepositoryInterface;
use App\Models\Link;
use App\Models\LinkDetails;
use Illuminate\Support\Collection;


class LinkRepository implements LinkRepositoryInterface
{
 //   protected Link $link;
   // protected UserController $user;

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
     * @throws \Exception
     */
    public function update(int $linkId, ?string $shortCode, LinkDetails $linkDetails) : Link
    {
        if($this->link->find($linkId)) {
            if($this->equalUserId($linkId)) {
                $link = $this->link->find($linkId);

                if($this->link->where('shortCode', $shortCode)->first())
                    throw new \Exception('This shortcode already exists');
                $link->update([
                    'shortCode' => $shortCode ?? $link->getShortCode(),
                    'isPublic' => $linkDetails->getIsPublic() ?? $link->getIsPublic(),
                    'originalUrl' => $linkDetails->getOriginalUrl() ?? $link->getOriginalUrl(),
                ]);
                return $link;
            }
            else throw new \Exception('you dont have access');
        } else throw new \Exception('this link doesnt exist');
    }

    /**
     * @throws \Exception
     */
    public function delete(int $linkId) : void
    {
        if($this->link->find($linkId)) {
            if ($this->equalUserId($linkId)) {
                $link = $this->link->find($linkId);
                $link->delete();
            }
            else throw new \Exception('you dont have access');
        } else throw new \Exception('this link doesnt exist');
    }

    /**
     * @throws \Exception
     */
    public function getById(int $linkId) : Link
    {
        if($this->link->find($linkId)) {
            if($this->equalUserId($linkId)) {
                return $this->link->find($linkId);
            }
            else throw new \Exception('you dont have access');
        } else throw new \Exception('this link doesnt exist');
    }

    /**
     * @throws \Exception
     */
    public function getOriginalLink(string $shortCode) : Link
    {
        return $this->link->where('shortCode', $shortCode)->get('originalUrl')->first()
            ?? throw new \Exception('this link doesnt exist');
    }

    /**
     * @throws \Exception
     */
    public function getByShortCode(string $shortCode) : Link
    {
        return $this->link->where('shortCode', $shortCode)->first()
            ?? throw new \Exception('this link doesnt exist');
    }

    public function getAll() : Collection
    {
        return $this->link->get();
    }

    public function getAllByUser(int $userId) : Collection
    {
        return $this->link->where('userId', $userId)->get();
    }

    private function equalUserId($linkId): bool
    {
        $userId = $this->link->where('id', $linkId)->get('userId');

        return $this->user->getId() === $userId[0]['userId'];
    }
}

