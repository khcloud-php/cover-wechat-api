<?php

namespace App\Support\Traits;

use App\Exceptions\BusinessException;
use App\Enums\ApiCodeEnum;

trait ServiceException
{
    /**
     * 业务异常返回
     * @param string|int $code
     * @param string $message
     * @throws BusinessException
     */
    public function throwBusinessException(string|int $code = ApiCodeEnum::FAILED_DEFAULT, string $message = '')
    {
        throw new BusinessException($code, $message);
    }
}
