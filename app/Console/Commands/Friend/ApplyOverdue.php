<?php

namespace App\Console\Commands\Friend;

use App\Enums\Database\FriendEnum;
use App\Models\Friend;
use Illuminate\Console\Command;

class ApplyOverdue extends Command
{

    protected $signature = 'Friend:applyOverdue';

    protected $description = 'Friend apply auto overdue.';

    public function handle(): void
    {
        Friend::query()
            ->where('type', FriendEnum::TYPE_APPLY)
            ->where('status', FriendEnum::STATUS_CHECK)
            ->where('display', 1)
            ->where('updated_at', '<', time() - 10 * 86400)
            ->update(['type' => FriendEnum::TYPE_VERIFY, 'status' => FriendEnum::STATUS_OVERDUE]);
    }
}
