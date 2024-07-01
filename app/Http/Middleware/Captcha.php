<?php

namespace App\Http\Middleware;

use App\Enums\ApiCodeEnum;
use App\Exceptions\BusinessException;
use App\Support\Traits\ServiceException;
use Closure;
use Exception;
use Fastknife\Service\BlockPuzzleCaptchaService;

class Captcha
{
    use ServiceException;

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     * @throws BusinessException
     */
    public function handle($request, Closure $next)
    {
        $captchaVerification = $request->input('captchaVerification');
        if (empty($captchaVerification)) {
            $this->throwBusinessException(ApiCodeEnum::SERVICE_CODE_ERROR);
        }
        //滑块验证
        $captchaService = new BlockPuzzleCaptchaService(config('captcha'));
        try {
            $captchaService->verificationByEncryptCode($captchaVerification);
        } catch (Exception $e) {
            $this->throwBusinessException(ApiCodeEnum::SYSTEM_ERROR, $e->getMessage());
        }
        return $next($request);
    }
}
