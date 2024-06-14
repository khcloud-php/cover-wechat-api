<?php

namespace App\Workerman;

use App\Enums\WorkerManEnum;
use App\Workerman\Action\User;
use Illuminate\Support\Facades\Log;

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

    public static function onMessage($clientId, $message)
    {
        $data = json_decode($message, true);
        $who = ucfirst($data['who']);
        $class = "\\App\\Workerman\\Action\\{$who}";
        $action = $data['action'];
        Log::channel(WorkerManEnum::LOG_CHANNEL)->info("{$class}->{$action} 收到消息：{$message}");
        if (class_exists($class) && method_exists($class, $action)) {
            $class::getInstance()->$action($clientId, $data['data']);
        } else {
            Log::channel(WorkerManEnum::LOG_CHANNEL)->error("{$class}->{$action} 类或方法不存在");
        }
    }

    public static function onClose($clientId)
    {
        echo "{$clientId} 离线了\n";
        User::getInstance()->logout($clientId);
    }
}
