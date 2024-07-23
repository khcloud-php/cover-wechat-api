<?php

namespace App\Http\Controllers;

use App\Exceptions\BusinessException;
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

    public function list(Request $request): \Illuminate\Http\JsonResponse
    {
        $data = $this->groupService->list($this->params);
        return $this->success($data, $request);
    }

    /**
     * @throws BusinessException
     * @throws ValidationException
     */
    public function action(Request $request): \Illuminate\Http\JsonResponse
    {
        $this->validate($request, [
            'group_users' => 'required|array',
            'action' => 'required|string',
        ]);
        $data = $this->groupService->action($this->params);
        return $this->success($data, $request);
    }
}
