<?php

namespace App\Http\Controllers;

use App\Services\ChatService;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    private ChatService $chatService;

    public function __construct()
    {
        parent::__construct();
        $this->chatService = new ChatService();
    }

    public function list(Request $request): \Illuminate\Http\JsonResponse
    {
        $list = $this->chatService->list($this->params);
        return $this->success($list, $request);
    }
}
