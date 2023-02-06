<?php
declare(strict_types=1);

namespace App\Services;

use App\Helpers\Utilites\ShortLinkGenerator;
use App\Interfaces\LinkRepositoryInterface;
use App\Interfaces\LinkServiceInterface;
use App\Models\LinkDetails;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use InvalidArgumentException;
use App\Models\Link;
use App\Exceptions\OriginalLinkAlreadyExistsException;


class LinkService implements LinkServiceInterface
{
    public function __construct(
        protected LinkRepositoryInterface $linkRepository, 
        private Link $link,
        private ShortLinkGenerator $shortLink)
    {
    }

    public function getAll()
    {
        $items = $this->linkRepository->getAll();
        return $this->paginate($items);
    }

    public function getAllByUser(int $userId)
    {
        $items = $this->linkRepository->getAllByUser($userId);
        return $this->paginate($items); //свой метод пагинации т к разбиваем коллекцию
        //поменять на стороннюю функцию
    }

    public function getById(int $linkId)
    {
        return $this->linkRepository->getById($linkId);
    }

    public function getByShortCode(string $shortCode)
    {
        return $this->linkRepository->getByShortCode($shortCode);
    }

    public function getOriginalLink(string $shortCode)
    {
        return $this->linkRepository->getOriginalLink($shortCode);
    }

    public function create(int $userId, LinkDetails $linkDetails, ?bool $recreate = false)
    {
        $links = $this->linkRepository->getByOriginalLink($linkDetails->getOriginalUrl());
        //ссылка существует (возможно у нескольких пользователей)
        
        foreach ($links as $link)
        {
            $linkUserId = $link->userId; //id всех по отдельности
            if ($linkUserId === $userId) {
                if(!$recreate)
                    throw new OriginalLinkAlreadyExistsException('This link already exists.');
                else {
                    $id = $this->linkRepository->getIdByUrlAndUserId($linkDetails->getOriginalUrl(), $userId);
                    return $this->linkRepository->update($id, null, $linkDetails);
                }
            }
        }
        //выставляем параметры ссылки
        $this->link->setUserId($userId);
        $this->link->setIsPublic($linkDetails->getIsPublic());
        $this->link->setShortCode($this->shortLink->generateShortLink($linkDetails->getOriginalUrl(), $userId));
        $this->link->setOriginalUrl($linkDetails->getOriginalUrl());
        $this->link->setCreatedDate(date("y-m-d"));

        return $this->linkRepository->create($this->link);
    }

    public function update(int $linkId, ?string $shortCode, LinkDetails $linkDetails)
    {
        DB::beginTransaction();
        try {
            $link = $this->linkRepository->update($linkId, $shortCode,
                $linkDetails);
        } catch (\Exception $e)
        {
            DB::rollBack();
            Log::info($e->getMessage());

            throw new InvalidArgumentException($e->getMessage());
        }
        DB::commit();

        return $link;
    }

    public function delete(int $linkId)
    {
        DB::beginTransaction();
        try {
            $this->linkRepository->delete($linkId);
        }
        catch (\Exception $e)
        {
            DB::rollBack();
            Log::info($e->getMessage());

            throw new InvalidArgumentException($e->getMessage());
        }
        DB::commit();
    }

    private function paginate($items, $perPage = 10, $page = null, $options = [])
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof Collection ? $items : Collection::make($items);
        return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
    }
}


