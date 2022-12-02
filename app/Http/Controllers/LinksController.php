<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\StoreLinkRequest;
use App\Models\LinkDetails;
use App\Services\LinkService;
use App\Traits\HttpResponses;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LinksController extends Controller
{
    use HttpResponses;

    protected LinkService $linkService;
    protected UserController $user;
    protected LinkDetails $linkDetails;

    public function __construct(LinkService $linkService, UserController $user, LinkDetails $linkDetails)
    {
        $this->linkService = $linkService;
        $this->user = $user;
        $this->linkDetails = $linkDetails;
    }

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
    public function getByShortCode($shortCode): JsonResponse
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
    public function getOriginalLink($shortCode): JsonResponse
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
    public function getAllByUser($userId): JsonResponse
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
        $this->linkDetails->setIsPublic($request->isPublic);

        try {
            $link = $this->linkService->create($this->user->getId(), $this->linkDetails);
        } catch (\Exception $e) {
            return $this->error('', $e->getMessage(), 500);
        }

        return $this->success($link);
    }

    /**
     * Display the specified resource.
     *
     * @param $linkId
     *
     * @return JsonResponse
     */
    public function show($linkId): JsonResponse
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
     * @param Request $request
     * @param         $linkId
     *
     * @return JsonResponse
     */
    public function update(Request $request, $linkId): JsonResponse
    {
        //изменение уже созданной ссылки
        $this->linkDetails->setIsPublic($request['isPublic']);
        $this->linkDetails->setOriginalUrl($request['originalUrl']);
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
