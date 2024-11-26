<?php

namespace App\Http\Controllers;

use App\Exceptions\BusinessException;
use App\Services\GroupService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class GroupController extends Controller
{
    private GroupService $groupService;

    public function __construct()
    {
        parent::__construct();
        $this->groupService = new GroupService();
    }

    /**
     * 群聊列表
     * @param Request $request
     * @return JsonResponse
     */
    public function list(Request $request): JsonResponse
    {
        $data = $this->groupService->list($this->params);
        return $this->success($data, $request);
    }

    /**
     * 创建群聊或邀请好友进群
     * @throws BusinessException
     * @throws ValidationException
     */
    public function action(Request $request): JsonResponse
    {
        $this->validate($request, [
            'group_users' => 'required|array',
            'action' => 'required|string',
        ]);
        $data = $this->groupService->action($this->params);
        return $this->success($data, $request);
    }
}
