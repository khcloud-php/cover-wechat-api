<?php

namespace App\Http\Controllers;

use App\Exceptions\BusinessException;
use App\Services\MessageService;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class MessageController extends Controller
{
    private MessageService $messageService;

    public function __construct()
    {
        parent::__construct();
        $this->messageService = new MessageService();
    }

    /**
     * 消息列表
     * @throws ValidationException
     */
    public function list(Request $request): \Illuminate\Http\JsonResponse
    {
        $this->validate($request, [
            'to_user' => 'required',
            'is_group' => 'required'
        ]);
        $list = $this->messageService->list($this->params);
        return $this->success($list, $request);
    }

    /**
     * 发送消息
     * @throws BusinessException
     * @throws ValidationException
     */
    public function send(Request $request): \Illuminate\Http\JsonResponse
    {
        $this->validate($request, [
            'to_user' => 'required',
            'content' => 'required',
            'type' => 'required',
            'is_group' => 'required'
        ]);
        $send = $this->messageService->send($this->params);
        return $this->success($send, $request);
    }

    /**
     * 消息已读
     * @throws ValidationException
     */
    public function read(Request $request): \Illuminate\Http\JsonResponse
    {
        $this->validate($request, [
            'to_user' => 'required'
        ]);
        $this->messageService->read($this->params);
        return $this->success([], $request);
    }

    /**
     * 消息撤回
     * @throws ValidationException
     */
    public function undo(Request $request): \Illuminate\Http\JsonResponse
    {
        $this->validate($request, [
            'id' => 'required'
        ]);
        $this->messageService->undo($this->params);
        return $this->success([], $request);
    }

    public function unread(Request $request): \Illuminate\Http\JsonResponse
    {
        $unread = $this->messageService->unread($request->user()->id);
        return $this->success($unread, $request);
    }
}
