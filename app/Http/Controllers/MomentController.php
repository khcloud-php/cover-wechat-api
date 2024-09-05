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


    /**
     * @throws ValidationException
     */
    public function list(Request $request): \Illuminate\Http\JsonResponse
    {
        $this->validate($request, [
            'page' => 'integer|min:1',
            'limit' => 'integer|min:1|max:20',
        ]);
        $data = $this->momentService->list($this->params);
        return $this->setPageInfo($data[0])->success($data[1], $request);
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

    /**
     * @throws BusinessException
     * @throws ValidationException
     */
    public function like(Request $request): \Illuminate\Http\JsonResponse
    {
        $this->validate($request, [
            'id' => 'required'
        ]);
        $data = $this->momentService->like($this->params);
        return $this->success($data, $request);
    }

    /**
     * @throws BusinessException
     * @throws ValidationException
     */
    public function unlike(Request $request): \Illuminate\Http\JsonResponse
    {
        $this->validate($request, [
            'id' => 'required'
        ]);
        $data = $this->momentService->unlike($this->params);
        return $this->success($data, $request);
    }

    /**
     * @throws BusinessException
     * @throws ValidationException
     */
    public function delete(Request $request): \Illuminate\Http\JsonResponse
    {
        $this->validate($request, [
            'id' => 'required'
        ]);
        $data = $this->momentService->delete($this->params);
        return $this->success($data, $request);
    }
}
