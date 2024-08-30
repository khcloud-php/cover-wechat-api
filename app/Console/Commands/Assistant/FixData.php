<?php

namespace App\Console\Commands\Assistant;

use App\Models\Group;
use App\Models\User;
use App\Services\AssistantService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class FixData extends Command
{

    protected $signature = 'Assistant:fixData';

    protected $description = 'Fix assistant data.';

    public function handle(): void
    {
        DB::beginTransaction();
        try {
            $assistant = config('assistant');
            $batchAssistantData = [];
            $historyAssistantIds = [];
            foreach ($assistant as $k => $v) {
                if ($v['history']) {
                    $historyAssistantIds[] = $k;
                    $batchAssistantData[] = [
                        'id' => $k,
                        'wechat' => $v['wechat'],
                        'mobile' => str_pad($k, 11, '0'),
                        'password' => $v['account_id'],
                        'token' => $v['token'],
                        'nickname' => $v['nickname'],
                        'avatar' => $v['avatar'],
                        'sign' => $v['desc'],
                        'gender' => 'female',
                        'setting' => json_encode(config('user.owner.setting')),
                        'created_at' => time(),
                    ];
                }
            }
            if ($batchAssistantData) {
                User::query()->insert($batchAssistantData);
                $assistantService = new AssistantService($historyAssistantIds);
                $assistantIds = get_assistant_ids();
                $userList = User::query()->whereNotIn('id', $assistantIds)->get()->toArray();
                foreach ($userList as $user) {
                    $assistantService->becomeFriendWhenRegister($user);
                }
                $groupList = Group::query()->get()->toArray();
                foreach ($groupList as $group) {
                    $assistantService->joinGroupWhenCreateGroup($group['id']);
                }
                DB::commit();
            }
        } catch (\Exception $e) {
            DB::rollBack();
        }
    }
}
