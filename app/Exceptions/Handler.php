<?php

namespace App\Exceptions;

use App\Enums\ApiCodeEnum;
use App\Models\ExceptionLog;
use App\Support\Traits\ServiceException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Queue\MaxAttemptsExceededException;
use Illuminate\Validation\ValidationException;
use Laravel\Lumen\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    use ServiceException;

    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        AuthorizationException::class,
        HttpException::class,
        ModelNotFoundException::class,
        ValidationException::class,
        BusinessException::class,
        MaxAttemptsExceededException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param \Throwable $exception
     * @return void
     *
     * @throws \Exception
     */
    public function report(Throwable $exception)
    {
        parent::report($exception);

        if ($this->shouldReport($exception)) {
            $code = $exception->getCode();
            $message = $exception->getMessage();
            $file = $exception->getFile();
            $line = $exception->getLine();
            $exString = sprintf(" %s \n code: %s \n file: %s \n line: %s", $message, $code, $file, $line);
            $request = request();
            switch (class_basename(get_class($exception))) {
                //                case 'PayException':
                //                    $title = "支付异常";
                //                    break;
                //                case 'SocialiteException':
                //                    $title = "社区化异常";
                //                    break;
                default:
                    $title = "系统异常";
            }
            $data = [
                'env' => config('app.env'),
                'project' => config('app.name'),
                'url' => $request->fullUrl(),
                'request_ip' => $request->getClientIp(),
                'request_id' => $request->offsetGet('request_id'),
                'exception' => $exString,
                'created_at' => time()
            ];
            ExceptionLog::query()->insert($data);
            //            $title = '**<font color="darkred"> ' . $title . ' </font>**';
            //            (new Robot())->notify(new Warning($title . "  \n\n  " . implode("  \n\n  ", $messageArray)));
        }
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Throwable $exception
     * @return \Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $exception)
    {

        if ($exception instanceof ThrottleRequestsException) {
            $this->throwBusinessException(ApiCodeEnum::CLIENT_ACTION_COUNT_ERROR);
        }
        // 请求类型错误异常抛出
        if ($exception instanceof MethodNotAllowedHttpException) {
            $this->throwBusinessException(ApiCodeEnum::CLIENT_METHOD_HTTP_TYPE_ERROR);
        }
        // 参数校验错误异常抛出
        if ($exception instanceof ValidationException) {
            $error = $exception->validator->errors()->first();
            $this->throwBusinessException(ApiCodeEnum::CLIENT_PARAMETER_ERROR, $error);
        }
        // Model 查不到数据
        if ($exception instanceof ModelNotFoundException) {
            $this->throwBusinessException(ApiCodeEnum::CLIENT_DATA_NOT_FOUND);
        }

        // 自定义错误异常抛出
        if ($exception instanceof BusinessException) {
            // Log::channel(LoggerEnum::LOGGER_DEFAULT)->error(sprintf('code:%s msg:%s', $exception->getCode(), $exception->getMessage()), $request->input());

            return response()->json([
                'code' => $exception->getCode(),
                'msg' => $exception->getFile() . $exception->getLine() . ':' . $exception->getMessage(),
                'data' => null,
                'request_id' => $request->offsetGet('request_id')
            ]);
        }
        return parent::render($request, $exception);
    }
}
