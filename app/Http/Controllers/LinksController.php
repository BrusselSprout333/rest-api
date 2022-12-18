<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\StoreLinkRequest;
use App\Http\Requests\UpdateLinkRequest;
use App\Interfaces\LinkServiceInterface;
use App\Models\Link;
use App\Models\LinkDetails;
use App\Traits\HttpResponses;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Redis;
use PHPUnit\Runner\Exception;

class LinksController extends Controller
{
    use HttpResponses;

    public function __construct(
        protected LinkServiceInterface $linkService,
        protected UserController $user,
        protected LinkDetails $linkDetails,
        protected NotificationsController $notification,
        private Link $link
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
        $recreate = (bool)$request->recreate;

        try {
            $link = $this->linkService->create($this->user->getId(), $this->linkDetails, $recreate);
        } catch (\Exception $e) {
            return $this->error('', $e->getMessage(), 500);
        }

        $data = [$this->user->getEmail(), $this->user->getPhone(), $this->linkDetails->getOriginalUrl()];
        Redis::publish('link_created', implode(",", $data));

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
        $shortCode = $request->shortCode ?? null;

        try {
            $link = $this->linkService->update($linkId, $shortCode, $this->linkDetails);
        } catch (\Exception $e) {
            return $this->error('', $e->getMessage(), 500);
        }

        $data = [$this->user->getEmail(), $this->user->getPhone(), $link->originalUrl];
        Redis::publish('link_updated', implode(",", $data));

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
            if($this->link->find($id)) {
                $originalUrl = $this->link->find($id)->get('originalUrl')->first()['originalUrl'];
            } else throw new Exception('this link doesnt exist');
            $this->linkService->delete($id);
        } catch (\Exception $e) {
            return $this->error('', $e->getMessage(), 500);
        }

        $data = [$this->user->getEmail(), $this->user->getPhone(), $originalUrl];

        Redis::publish('link_deleted', implode(",", $data));

        return $this->success([
            'message' => 'link was deleted'
        ]);
    }
}
