<?php

namespace App\Workerman;

use App\Enums\WorkerManEnum;
use App\Workerman\Action\User;
use Illuminate\Support\Facades\Log;
use Psr\SimpleCache\InvalidArgumentException;

class Events
{

    public static function onWorkerStart($businessWorker)
    {
    }

    public static function onConnect($clientId)
    {
    }

    public static function onWebSocketConnect($clientId, $data)
    {
    }

    public static function onMessage($clientId, $message): void
    {
        $data = json_decode($message, true);
        $who = ucfirst($data['who']);
        $class = "\\App\\Workerman\\Action\\{$who}";
        $action = $data['action'];
        Log::channel(WorkerManEnum::LOG_CHANNEL)->info("{$class}->{$action} 收到消息：{$message}");
        if (class_exists($class) && method_exists($class, $action)) {
            (new $class)->$action($clientId, $data['data']);
        } else {
            Log::channel(WorkerManEnum::LOG_CHANNEL)->error("{$class}->{$action} 类或方法不存在");
        }
    }

    /**
     * @throws InvalidArgumentException
     */
    public static function onClose($clientId): void
    {
        echo "clientId:{$clientId} 离线了\n";
//        (new User)->logout($clientId);
    }
}
