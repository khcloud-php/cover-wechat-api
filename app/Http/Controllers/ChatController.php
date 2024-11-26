<?php

namespace App\Http\Controllers;

use App\Exceptions\BusinessException;
use App\Services\ChatService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ChatController extends Controller
{
    private ChatService $chatService;

    public function __construct()
    {
        parent::__construct();
        $this->chatService = new ChatService();
    }

    /**
     * 聊天列表
     * @param Request $request
     * @return JsonResponse
     */
    public function list(Request $request): JsonResponse
    {
        $data = $this->chatService->list($this->params);
        return $this->success($data, $request);
    }

    /**
     * 聊天详情
     * @param Request $request
     * @return JsonResponse
     * @throws ValidationException
     */
    public function info(Request $request): JsonResponse
    {
        $this->validate($request, [
            'to_user' => 'required',
            'is_group' => 'required'
        ]);
        $data = $this->chatService->info($this->params);
        return $this->success($data, $request);
    }

    /**
     * 置顶聊天
     * @param Request $request
     * @return JsonResponse
     * @throws ValidationException
     */
    public function top(Request $request): JsonResponse
    {
        $this->validate($request, [
            'to_user' => 'required',
            'is_group' => 'required',
            'is_top' => 'required'
        ]);
        $data = $this->chatService->top($this->params);
        return $this->success($data, $request);
    }

    /**
     * 隐藏聊天
     * @param Request $request
     * @return JsonResponse
     * @throws ValidationException
     */
    public function hide(Request $request): JsonResponse
    {
        $this->validate($request, [
            'to_user' => 'required',
            'is_group' => 'required'
        ]);
        $data = $this->chatService->hide($this->params);
        return $this->success($data, $request);
    }

    /**
     * 设置聊天
     * @param Request $request
     * @return JsonResponse
     * @throws BusinessException
     * @throws ValidationException
     */
    public function update(Request $request): JsonResponse
    {
        $this->validate($request, [
            'to_user' => 'required',
            'is_group' => 'required'
        ]);
        $this->chatService->update($this->params);
        return $this->success([], $request);
    }

    /**
     * 删除聊天
     * @param Request $request
     * @return JsonResponse
     * @throws BusinessException
     * @throws ValidationException
     */
    public function delete(Request $request): JsonResponse
    {
        $this->validate($request, [
            'to_user' => 'required',
            'is_group' => 'required'
        ]);
        $data = $this->chatService->delete($this->params);
        return $this->success($data, $request);
    }
}
