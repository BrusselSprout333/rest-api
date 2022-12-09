<?php
declare(strict_types=1);

namespace App\Services;

use App\Interfaces\LinkRepositoryInterface;
use App\Interfaces\LinkServiceInterface;
use App\Models\LinkDetails;
use App\Repositories\LinkRepository;
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

    public function getAllByUser(int $userId)
    {
        return $this->linkRepository->getAllByUser($userId);
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


