<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Exceptions\OriginalLinkAlreadyExistsException;
use App\Http\Requests\StoreLinkRequest;
use App\Http\Requests\UpdateLinkRequest;
use App\Interfaces\LinkServiceInterface;
use App\Models\Link;
use App\Models\LinkDetails;
use App\Services\LinkService;
use App\Traits\HttpResponses;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LinksController extends Controller
{
    use HttpResponses;

    public function __construct(
        protected LinkServiceInterface $linkService,
        protected UserController $user,
        protected LinkDetails $linkDetails,
        protected Link $link
    ) {}

    /**
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        try {
            $links = $this->linkService->getAll();
        } catch (\Exception $e)
        {
            return $this->error('', $e->getMessage(), 500);
        }

        return $this->success([
            'links' => $links
        ]);
    }

    /**
     * Display a link by shortcode.
     *
     * @param $shortCode
     *
     * @return JsonResponse
     */
    public function getByShortCode(string $shortCode): JsonResponse
    {
        try {
            $link = $this->linkService->getByShortCode($shortCode);
        } catch (\Exception $e) {
            return $this->error('', $e->getMessage(), 500);
        }

        return $this->success([
            'link' => $link
        ]);
    }

    /**
     * @param $shortCode
     *
     * @return JsonResponse
     */
    public function getOriginalLink(string $shortCode): JsonResponse
    {
        try {
            $originalUrl = $this->linkService->getOriginalLink($shortCode);
        } catch (\Exception $e) {
            return $this->error('', $e->getMessage(), 500);
        }

        return $this->success($originalUrl);
    }

    /**
     * Display a link by shortcode.
     *
     * @param $userId
     *
     * @return JsonResponse
     */
    public function getAllByUser(int $userId): JsonResponse
    {
        try {
            $links = $this->linkService->getAllByUser($userId);
        } catch (\Exception $e) {
            return $this->error('', $e->getMessage(), 500);
        }

        return $this->success([
            'links' => $links
        ]);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param StoreLinkRequest $request
     *
     * @return JsonResponse
     */
    public function store(StoreLinkRequest $request): JsonResponse
    {
        //создание ссылки
        $this->linkDetails->setOriginalUrl($request->originalUrl);
        $this->linkDetails->setIsPublic((bool)$request->isPublic);

        try {
            if($this->link->where('originalUrl', $this->linkDetails->getOriginalUrl())->first()) {
                throw new OriginalLinkAlreadyExistsException('Note: This link already exists. It will be recreated');
            }
            $link = $this->linkService->create($this->user->getId(), $this->linkDetails);
        } catch (\Exception $e) {
            return $this->error('', $e->getMessage(), 500);
        }
//        catch (\OriginalLinkAlreadyExistsException $e)
//        {
//            $link = $this->linkService->create($this->user->getId(), $this->linkDetails, true);
//        }

        return $this->success($link);
    }

    /**
     * Display the specified resource.
     *
     * @param $linkId
     *
     * @return JsonResponse
     */
    public function show(int $linkId): JsonResponse
    {
        try {
            $link = $this->linkService->getById($linkId);
        } catch (\Exception $e) {
            return $this->error('', $e->getMessage(), 500);
        }

        return $this->success($link);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param UpdateLinkRequest $request
     * @param int               $linkId
     *
     * @return JsonResponse
     */
    public function update(UpdateLinkRequest $request, int $linkId): JsonResponse
    {
        //изменение уже созданной ссылки
        $this->linkDetails->setIsPublic((bool)$request->isPublic);
        $this->linkDetails->setOriginalUrl($request->originalUrl);
        $shortCode = $request['shortCode'] ?? null;

        try {
            $link = $this->linkService->update($linkId, $shortCode, $this->linkDetails);
        } catch (\Exception $e) {
            return $this->error('', $e->getMessage(), 500);
        }

        return $this->success($link);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $this->linkService->delete($id);
        } catch (\Exception $e) {
            return $this->error('', $e->getMessage(), 500);
        }

        return $this->success([
            'message' => 'link was deleted'
        ]);
    }
}
