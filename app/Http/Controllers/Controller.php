<?php

namespace App\Http\Controllers;

use App\Enums\ApiCodeEnum;
use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller as BaseController;

class Controller extends BaseController
{
    protected array $params;

    public function __construct()
    {
        $this->params = request()->all();
        if (empty($this->params['user']) && request()->user())
            $this->params['user'] = request()->user();
    }

    //
    public function success(array $data, Request $request, string|int $code = ApiCodeEnum::SUCCESS_DEFAULT): \Illuminate\Http\JsonResponse
    {
        $message = '';
        if (stripos($code, '|') !== false) {
            $arr = explode('|', $code);
            $message = $arr[1];
            $code = $arr[0];
        }
        return response()->json([
            'code' => $code,
            'msg' => $message,
            'data' => $data,
            'request_id' => $request->offsetGet('request_id')
        ]);
    }
}
