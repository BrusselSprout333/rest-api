<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLinkRequest;
use App\Http\Resources\LinksResource;
use App\Models\Link;
use App\Services\LinkService;
use App\Traits\HttpResponses;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LinksController extends Controller
{
    use HttpResponses;

    protected LinkService $linkService;

    public function __construct(LinkService $linkService)
    {
        $this->linkService = $linkService;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
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
     * Store a newly created resource in storage.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreLinkRequest $request)
    {
        //создание ссылки
        $data = $request->only([
            'originalUrl',
            'isPublic',
        ]);

        try {
            $link = $this->linkService->create($data);
        } catch (\Exception $e)
        {
            return $this->error('', $e->getMessage(), 500);
        }

        return $this->success([
            'link' => $link
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     *
     * @return JsonResponse
     */
    public function show($id): JsonResponse
    {
        try {
            $link = $this->linkService->getById($id);
        } catch (\Exception $e) {
            return $this->error('', $e->getMessage(), 500);
        }

        return $this->success([
            'link' => $link
        ]);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param  int    $id
     *
     * @return JsonResponse
     */
    public function update(Request $request, $id)
    {
        //изменение уже созданной ссылки
        $data = $request->only([
            'originalUrl',
            'isPublic',
            'shortCode',
        ]);

        try {
            $link = $this->linkService->update($data, $id);
        } catch (\Exception $e) {
            return $this->error('', $e->getMessage(), 500);
        }

        return $this->success([
            'link' => $link
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     *
     * @return JsonResponse
     */
    public function destroy($id)
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
