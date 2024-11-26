<?php

namespace App\Http\Controllers;

use App\Exceptions\BusinessException;
use App\Services\MomentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class MomentController extends Controller
{
    private MomentService $momentService;

    public function __construct()
    {
        parent::__construct();
        $this->momentService = new MomentService();
    }


    /**
     * 朋友圈列表
     * @throws ValidationException
     */
    public function list(Request $request): JsonResponse
    {
        $this->validate($request, [
            'page' => 'integer|min:1',
            'limit' => 'integer|min:1|max:20',
        ]);
        $data = $this->momentService->list($this->params);
        return $this->setPageInfo($data[0])->success($data[1], $request);
    }

    /**
     * 朋友圈详情
     * @throws ValidationException
     */
    public function detail(Request $request): JsonResponse
    {
        $this->validate($request, [
            'id' => 'required',
        ]);
        $data = $this->momentService->detail($this->params);
        return $this->success($data, $request);
    }

    /**
     * 发朋友圈
     * @throws ValidationException
     * @throws BusinessException
     */
    public function publish(Request $request): JsonResponse
    {
        $this->validate($request, [
            'type' => 'required',
            'content' => 'required'
        ]);
        $data = $this->momentService->publish($this->params);
        return $this->success($data, $request);
    }

    /**
     * 喜欢朋友圈
     * @throws BusinessException
     * @throws ValidationException
     */
    public function like(Request $request): JsonResponse
    {
        $this->validate($request, [
            'id' => 'required'
        ]);
        $data = $this->momentService->like($this->params);
        return $this->success($data, $request);
    }

    /**
     * 取消喜欢
     * @throws BusinessException
     * @throws ValidationException
     */
    public function unlike(Request $request): JsonResponse
    {
        $this->validate($request, [
            'id' => 'required'
        ]);
        $data = $this->momentService->unlike($this->params);
        return $this->success($data, $request);
    }

    /**
     * 评论朋友圈
     * @throws BusinessException
     * @throws ValidationException
     */
    public function comment(Request $request): JsonResponse
    {
        $this->validate($request, [
            'id' => 'required',
            'content' => 'required'
        ]);
        $data = $this->momentService->comment($this->params);
        return $this->success($data, $request);
    }

    /**
     * 朋友圈消息列表
     * @param Request $request
     * @return JsonResponse
     */
    public function message(Request $request): JsonResponse
    {
        $data = $this->momentService->message($this->params);
        return $this->setPageInfo($data[0])->success($data[1], $request);
    }

    /**
     * 删除朋友圈
     * @throws BusinessException
     * @throws ValidationException
     */
    public function delete(Request $request): JsonResponse
    {
        $this->validate($request, [
            'id' => 'required'
        ]);
        $data = $this->momentService->delete($this->params);
        return $this->success($data, $request);
    }
}
