<?php

namespace App\Http\Controllers;

use App\Exceptions\BusinessException;
use App\Services\ChatService;
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

    public function list(Request $request): \Illuminate\Http\JsonResponse
    {
        $data = $this->chatService->list($this->params);
        return $this->success($data, $request);
    }

    /**
     * @throws ValidationException
     */
    public function info(Request $request): \Illuminate\Http\JsonResponse
    {
        $this->validate($request, [
            'to_user' => 'required',
            'is_group' => 'required'
        ]);
        $data = $this->chatService->info($this->params);
        return $this->success($data, $request);
    }

    /**
     * @throws ValidationException
     */
    public function top(Request $request): \Illuminate\Http\JsonResponse
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
     * @throws ValidationException
     */
    public function hide(Request $request): \Illuminate\Http\JsonResponse
    {
        $this->validate($request, [
            'to_user' => 'required',
            'is_group' => 'required'
        ]);
        $data = $this->chatService->hide($this->params);
        return $this->success($data, $request);
    }

    /**
     * @throws BusinessException
     * @throws ValidationException
     */
    public function update(Request $request): \Illuminate\Http\JsonResponse
    {
        $this->validate($request, [
            'to_user' => 'required',
            'is_group' => 'required'
        ]);
        $this->chatService->update($this->params);
        return $this->success([], $request);
    }

    /**
     * @throws ValidationException
     */
    public function delete(Request $request): \Illuminate\Http\JsonResponse
    {
        $this->validate($request, [
            'to_user' => 'required',
            'is_group' => 'required'
        ]);
        $data = $this->chatService->delete($this->params);
        return $this->success($data, $request);
    }
}
