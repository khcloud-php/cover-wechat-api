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

        $message = $this->messageService->send($this->params);
        return $this->success($message, $request);
    }
}
