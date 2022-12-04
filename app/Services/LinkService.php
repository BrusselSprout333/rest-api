<?php
declare(strict_types=1);

namespace App\Services;

use App\Interfaces\LinkServiceInterface;
use App\Models\LinkDetails;
use App\Repositories\LinkRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use InvalidArgumentException;


class LinkService implements LinkServiceInterface
{
    public function __construct(protected LinkRepository $linkRepository)
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
        return $this->linkRepository->create($userId, $linkDetails, $recreate);
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


