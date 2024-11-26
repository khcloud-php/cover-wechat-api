<?php

namespace App\Http\Controllers;

use Fastknife\Exception\ParamException;
use Fastknife\Service\BlockPuzzleCaptchaService;
use Fastknife\Service\ClickWordCaptchaService;
use Illuminate\Http\Request;

class SystemController extends Controller
{
    /**
     * 获取验证码
     * @return array
     */
    public function get(): array
    {
        try {
            $service = $this->getCaptchaService();
            $data = $service->get();
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
        return $this->result($data);
    }

    /**
     * 一次验证
     * @param Request $request
     * @return array
     */
    public function check(Request $request): array
    {
        try {
            $data = $this->validate($request, [
                'token' => 'bail|required',
                'pointJson' => 'required',
            ]);
            $service = $this->getCaptchaService();
            $service->check($data['token'], $data['pointJson']);
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
        return $this->result([]);
    }

    /**
     * 二次验证
     * @param Request $request
     * @return array
     */
    public function verification(Request $request): array
    {
        try {
            $data = $this->validate($request, [
                'token' => 'bail|required',
                'pointJson' => 'required',
            ]);
            $service = $this->getCaptchaService();
            $service->verification($data['token'], $data['pointJson']);
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
        return $this->result([]);
    }

    protected function getCaptchaService(): ClickWordCaptchaService|BlockPuzzleCaptchaService
    {
        $captchaType = request()->post('captchaType', null);
        $config = config('captcha');
        return match ($captchaType) {
            "clickWord" => new ClickWordCaptchaService($config),
            "blockPuzzle" => new BlockPuzzleCaptchaService($config),
            default => throw new ParamException('captchaType参数不正确！'),
        };
    }

    private function result($data): array
    {
        return [
            'error' => false,
            'repCode' => '0000',
            'repData' => $data,
            'repMsg' => null,
            'success' => true,
        ];
    }

    private function error($msg): array
    {
        return [
            'error' => true,
            'repCode' => '6111',
            'repData' => null,
            'repMsg' => $msg,
            'success' => false,
        ];
    }
}
