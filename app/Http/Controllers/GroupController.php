<?php

namespace App\Http\Controllers;

use App\Services\GroupService;
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
     * 创建群聊
     * @throws ValidationException
     */
    public function create(Request $request): \Illuminate\Http\JsonResponse
    {
        $this->validate($request, [
            'group_users' => 'required|array',
        ]);
        $data = $this->groupService->create($this->params);
        return $this->success($data, $request);
    }
}
