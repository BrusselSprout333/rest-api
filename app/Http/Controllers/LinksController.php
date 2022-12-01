<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLinkRequest;
use App\Models\Link;
use App\Models\LinkDetails;
use App\Services\LinkService;
use App\Traits\HttpResponses;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getByShortCode($shortCode)
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
     * Store a newly created resource in storage.
     *
     * @param Request $request
     *

     */
    public function store(StoreLinkRequest $request)
    {
        //создание ссылки
        $this->linkDetails->originalUrl = $request->originalUrl;
        $this->linkDetails->isPublic = $request->isPublic;

        try {
            $link = $this->linkService->create($this->user->getId(), $this->linkDetails);
        } catch (\Exception $e)
        {
            return $this->error('', $e->getMessage(), 500);
        }

        return $this->success([
            'status' => true,
            'link' => $link
        ]);

//        return $this->success([
//            'userId' => $link->userId,
//            'originalUrl' => $link->originalUrl,
//            'shortCode' => $link->shortCode,
//            'isPublic' => $link->isPublic,
//            'createdDate' => $link->createdDate,
//        ]);


//        $link->originalUrl = $data['originalUrl'];
//        $link->isPublic = $data['isPublic'];
//        $link->userId = Auth::id();//$this->user->getId();
////        $this->link->shortCode = $this->createShortCode();
//        $link->createdDate = date("y-m-d");
//        // return $link;
//        return response()->json([
//            'status' => true,
//            'originalUrl' => $link->originalUrl,
//            'isPublic' => $link->isPublic,
//            'userId' => $link->userId,
//            'createdDate' => $link->createdDate,
//        ], 200);
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
    public function update(Request $request, $linkId)
    {
        //изменение уже созданной ссылки
        $data = $request->only([
            'originalUrl',
            'isPublic',
            'shortCode',
        ]);

//        $this->linkDetails->originalUrl = $request->originalUrl;
//        $this->linkDetails->isPublic = $request->isPublic;
//        $shortCode = $request->shortCode;

        try {
            $link = $this->linkService->update($data, $linkId);//, $shortCode, $this->linkDetails);
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
