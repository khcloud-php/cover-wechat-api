<?php

namespace App\Http\Controllers;

use App\Services\FriendService;
use Illuminate\Http\Request;

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
            'friend' => 'required',
            'setting' => 'required'
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
            'friend' => 'required',
            'setting' => 'required'
        ]);
        $verify = $this->friendService->verify($this->params);
        return $this->success($verify, $request);
    }
}
