<?php

namespace App\Jobs;

use App\Enums\Redis\ChatEnum;
use App\Services\AssistantService;

class AssistantReplyJob extends Job
{
    public $queue = ChatEnum::ASSISTANT_REPLY;

    protected array $data;

    // 设置最大重试次数
    public int $tries = 3;

    // 设置失败后等待的秒数
    public int $backoff = 10;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        //
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(): void
    {
        //
        $assistantService = new AssistantService();
        $assistantService->replyMessage($this->data);
    }
}
