<?php

namespace App\Console\Commands;

use GatewayWorker\BusinessWorker;
use GatewayWorker\Gateway;
use GatewayWorker\Register;
use Illuminate\Console\Command;
use Workerman\Worker;

class Workerman extends Command
{

    protected $signature = 'workerman
                            {action : action}
                            {--start=all : start}
                            {--d : daemon mode}';

    protected $description = 'Start a Workerman server.';

    public function handle(): void
    {
        global $argv;
        $action = $this->argument('action');

        /**
         * 针对 Windows 一次执行，无法注册多个协议的特殊处理
         */
        if ($action === 'single') {
            $start = $this->option('start');
            if ($start === 'register') {
                $this->startRegister();
            } elseif ($start === 'gateway') {
                $this->startGateWay();
            } elseif ($start === 'worker') {
                $this->startBusinessWorker();
            }
            Worker::runAll();

            return;
        }

        /**
         * argv[0] 默认是，当前文件，可以不修改
         */
        //$argv[0] = 'wk';
        $argv[1] = $action;
        // 控制是否进入 daemon 模式
        $argv[2] = $this->option('d') ? '-d' : '';

        $this->start();
    }

    private function start(): void
    {
        $this->startGateWay();
        $this->startBusinessWorker();
        $this->startRegister();
        Worker::runAll();
    }

    private function startBusinessWorker(): void
    {
        $worker = new BusinessWorker();
        $worker->name = 'BusinessWorker';
        $worker->count = 1;
        $worker->registerAddress = '127.0.0.1:1238';
        $worker->eventHandler = \App\Workerman\Events::class;
    }

    private function startGateWay(): void
    {
        $gateway = new Gateway("websocket://0.0.0.0:2346");
        $gateway->name = 'Gateway';
        $gateway->count = 1;
        $gateway->lanIp = '127.0.0.1';
        $gateway->startPort = 2300;
        $gateway->pingInterval = 30;
        $gateway->pingNotResponseLimit = 0;
        $gateway->pingData = '{"type":"@heart@"}';
        $gateway->registerAddress = '127.0.0.1:1238';
    }

    private function startRegister(): void
    {
        new Register('text://0.0.0.0:1238');
    }
}
