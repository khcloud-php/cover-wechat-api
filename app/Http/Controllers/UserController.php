<?php

namespace App\Http\Controllers;

use App\Enums\ApiCodeEnum;
use App\Exceptions\BusinessException;
use Illuminate\Http\Request;
use App\Services\UserService;

class UserController extends Controller
{

    private UserService $userService;

    public function __construct()
    {
        parent::__construct();
        $this->userService = new UserService();
    }

    /**
     * 用户注册
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Exceptions\BusinessException
     * @throws \Illuminate\Validation\ValidationException
     * @author yjf
     * @date 2024-05-10 11:44
     */
    public function register(Request $request): \Illuminate\Http\JsonResponse
    {
        $this->validate($request, [
            'wechat' => 'required|min:4|max:20',
            'nickname' => 'required|max:20',
            'mobile' => 'required|regex:/^1[3-9]\d{9}$/',
            'password' => 'required|min:6',
        ]);
        $user = $this->userService->register($this->params);
        return $this->success($user, $request, ApiCodeEnum::SERVICE_REGISTER_SUCCESS);
    }

    /**
     * 用户登录
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Exceptions\BusinessException
     * @throws \Illuminate\Validation\ValidationException
     * @author yjf
     * @date 2024-05-10 11:44
     */
    public function login(Request $request): \Illuminate\Http\JsonResponse
    {
        $this->validate($request, [
            'mobile' => 'required|regex:/^1[3-9]\d{9}$/',
            'password' => 'required|min:6',
            'code' => 'required|digits:4|int'
        ]);

        $user = $this->userService->login($this->params);
        return $this->success($user, $request, ApiCodeEnum::SERVICE_LOGIN_SUCCESS);
    }

    /**
     * 用户注销
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @author yjf
     * @date 2024-05-10 11:45
     */
    public function logout(Request $request): \Illuminate\Http\JsonResponse
    {
        $this->userService->logout($request->user()->id);
        return $this->success([], $request);
    }

    /**
     * 用户主页
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     * @throws BusinessException
     * @author yjf
     * @date 2024-05-10 11:33
     */
    public function home($keywords, Request $request): \Illuminate\Http\JsonResponse
    {
        $this->params['keywords'] = $keywords;
        $home = $this->userService->home($this->params);
        return $this->success($home, $request);
    }

    /**
     * 我
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function me(Request $request): \Illuminate\Http\JsonResponse
    {
        $userId = $this->params['user']->id;
        $me = $this->userService->me($userId);
        return $this->success($me, $request);
    }
}
