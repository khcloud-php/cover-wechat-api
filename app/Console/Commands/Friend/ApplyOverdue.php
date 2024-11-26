<?php

namespace App\Console\Commands\Friend;

use App\Enums\Database\FriendEnum;
use App\Models\Friend;
use Illuminate\Console\Command;

class ApplyOverdue extends Command
{

    protected $signature = 'Friend:applyOverdue';

    protected $description = 'Friend apply auto overdue.';

    /**
     * 好友申请自动过期
     * @return void
     */
    public function handle(): void
    {
        Friend::query()
            ->where('type', FriendEnum::TYPE_APPLY)
            ->where('status', FriendEnum::STATUS_CHECK)
            ->whereNotIn('friend', array_keys(config('assistant')))
            ->where('created_at', '<', time() - 3 * 86400)
            ->update(['type' => FriendEnum::TYPE_VERIFY, 'status' => FriendEnum::STATUS_OVERDUE]);
    }
}
