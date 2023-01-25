<?php
declare(strict_types=1);

namespace App\Services;

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
    public function __construct(protected LinkRepositoryInterface $linkRepository, private Link $link)
    {
    }

    public function getAll()
    {
        return $this->linkRepository->getAll();
    }

    public function paginate($items, $perPage = 10, $page = null, $options = [])
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof Collection ? $items : Collection::make($items);
        return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
    }

    public function getAllByUser(int $userId)
    {
        $items = $this->linkRepository->getAllByUser($userId);
       // return $items;
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
        if (!$recreate && $this->link->where('originalUrl', $linkDetails->getOriginalUrl())->first()) {
            throw new OriginalLinkAlreadyExistsException('This link already exists.');
        } else if ($recreate && $this->link->where('originalUrl', $linkDetails->getOriginalUrl())->first()) {
            $id = $this->link->where('originalUrl', $linkDetails->getOriginalUrl())->get('id')->first()['id'];
            return $this->linkRepository->update($id, null, $linkDetails);
        }
        return $this->linkRepository->create($userId, $linkDetails);
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
}


