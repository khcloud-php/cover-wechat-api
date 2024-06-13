<?php

namespace App\Workerman;

use App\Enums\WorkerManEnum;
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
        $who = "\\App\\Workerman\\" . ucfirst($data['who']); //打印User
        $action = $data['action']; //打印login
        Log::channel(WorkerManEnum::LOG_CHANNEL)->info("{$who}->{$action} 收到消息：{$message}");
        $who::getInstance()->$action($clientId, $data['data']);
    }

    public static function onClose($clientId)
    {
        echo "{$clientId} 离线了\n";
        User::getInstance()->logout($clientId);
    }
}
