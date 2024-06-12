<?php

namespace App\Http\Controllers;

use App\Services\FriendService;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class FriendController extends Controller
{
    private FriendService $friendService;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->friendService = new FriendService();
    }

    /**
     * 好友列表
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @author yjf
     * @date 2024-05-13 15:03
     */
    public function list(Request $request): \Illuminate\Http\JsonResponse
    {
        $friendList = $this->friendService->list($this->params);
        return $this->success($friendList, $request);
    }


    /**
     * 好友申请列表
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @author yjf
     * @date 2024-05-13 17:37
     */
    public function applyList(Request $request): \Illuminate\Http\JsonResponse
    {
        $applyList = $this->friendService->applyList($this->params);
        return $this->success($applyList, $request);
    }

    /**
     * 删除好友申请
     * @param $id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @author yjf
     * @date 2024-05-13 18:18
     */
    public function deleteApply($id, Request $request): \Illuminate\Http\JsonResponse
    {
        $userId = $this->params['user']->id;
        $this->friendService->deleteApply($id, $userId);
        return $this->success([], $request);
    }

    /**
     * 查找好友
     * @param $keywords
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @author yjf
     * @date 2024-05-13 10:10
     */
    public function search($keywords, Request $request): \Illuminate\Http\JsonResponse
    {
        $this->params['keywords'] = $keywords;
        $friend = $this->friendService->search($this->params);
        return $this->success($friend, $request);
    }

    public function showConfirm(Request $request): \Illuminate\Http\JsonResponse
    {
        $this->validate($request, [
            'source' => 'required|string',
        ]);
        $confirm = $this->friendService->showConfirm($this->params);
        return $this->success($confirm, $request);
    }

    /**
     * 申请添加好友
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Exceptions\BusinessException
     * @throws \Illuminate\Validation\ValidationException
     * @author yjf
     * @date 2024-05-13 11:22
     */
    public function apply(Request $request): \Illuminate\Http\JsonResponse
    {
        $this->validate($request, [
            'friend' => 'required|int',
            'nickname' => 'required|string',
            'setting' => 'required|array'
        ]);
        $apply = $this->friendService->apply($this->params);
        return $this->success($apply, $request);
    }

    /**
     * 通过好友申请
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     * @author yjf
     * @date 2024-05-13 14:49
     */
    public function verify(Request $request): \Illuminate\Http\JsonResponse
    {
        $this->validate($request, [
            'friend' => 'required|int',
            'nickname' => 'required|string',
            'setting' => 'required|array'
        ]);
        $verify = $this->friendService->verify($this->params);
        return $this->success($verify, $request);
    }

    /**
     * 更新朋友
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws ValidationException
     * @throws \App\Exceptions\BusinessException
     */
    public function update(Request $request): \Illuminate\Http\JsonResponse
    {
        $this->validate($request, [
            'friend' => 'required|int'
        ]);
        $update = $this->friendService->update($this->params);
        return $this->success($update, $request);
    }
}
