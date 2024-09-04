<?php

namespace App\Http\Controllers;

use App\Exceptions\BusinessException;
use App\Services\MomentService;
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


    public function list(Request $request): \Illuminate\Http\JsonResponse
    {
        $data = $this->momentService->list($this->params);
        return $this->success($data, $request);
    }

    /**
     * 发朋友圈
     * @throws ValidationException
     * @throws BusinessException
     */
    public function publish(Request $request): \Illuminate\Http\JsonResponse
    {
        $this->validate($request, [
            'type' => 'required',
            'content' => 'required'
        ]);
        $data = $this->momentService->publish($this->params);
        return $this->success($data, $request);
    }
}
