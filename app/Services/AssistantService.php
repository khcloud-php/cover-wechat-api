<?php

namespace App\Services;

use App\Enums\Database\FileEnum;
use App\Enums\Database\FriendEnum;
use App\Enums\Database\GroupEnum;
use App\Enums\Database\MessageEnum;
use App\Enums\Database\UserEnum;
use App\Enums\WorkerManEnum;
use App\Models\File;
use App\Models\Friend;
use App\Models\Group;
use App\Models\GroupUser;
use App\Models\Message;
use App\Models\User;
use GatewayWorker\Lib\Gateway;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class AssistantService extends BaseService
{
    private array $aiList;

    private int $time;

    private array $assistant;

    public function __construct(array $assistantIds = [])
    {
        $this->assistant = config('assistant');
        $this->time = time();
        if (!$assistantIds) $assistantIds = array_keys($this->assistant);
        // ai小助手
        $this->aiList = User::query()->whereIn('id', $assistantIds)->where('status', UserEnum::STATUS_NORMAL)->get()->toArray();
    }

    /**
     * 用户注册时让小助手成为朋友
     * @param array $user
     * @return void
     */
    public function becomeFriendWhenRegister(array $user): void
    {
        $batchAiData = [];
        $batchMessageData = [];
        foreach ($this->aiList as $ai) {
            $aiConf = $this->assistant[$ai['id']] ?? [];
            $batchAiData[] = [
                'owner' => $user['id'],
                'friend' => $ai['id'],
                'nickname' => $ai['nickname'],
                'type' => FriendEnum::TYPE_VERIFY,
                'status' => FriendEnum::STATUS_PASS,
                'unread' => 1,
                'top' => $this->time,
                'content' => $aiConf['desc'] ?? $ai['sign'],
                'time' => $this->time,
                'display' => 1,
                'remark' => $ai['sign'],
                'setting' => json_encode(config('user.friend.setting')),
                'source' => FriendEnum::SOURCE_ASSISTANT,
                'created_at' => $this->time
            ];
            $batchMessageData[] = [
                'from_user' => $ai['id'],
                'to_user' => $user['id'],
                'content' => $aiConf['desc'] ?? $ai['sign'],
                'created_at' => $this->time
            ];
        }
        Friend::query()->insert($batchAiData);
        Message::query()->insert($batchMessageData);
    }

    /**
     * 创建群聊时让小助手加入群聊
     * @param int $groupId
     * @return void
     */
    public function joinGroupWhenCreateGroup(int $groupId): void
    {
        $batchAiData = [];
        $batchMessageData = [];
        foreach ($this->aiList as $ai) {
            $batchAiData[] = [
                'group_id' => $groupId,
                'user_id' => $ai['id'],
                'role' => GroupEnum::ROLE_ASSISTANT,
                'invite_id' => 0,
                'unread' => 0,
                'setting' => '{}',
                'created_at' => $this->time
            ];
            $batchMessageData[] = [
                'from_user' => $ai['id'],
                'to_user' => $groupId,
                'content' => $aiConf['desc'] ?? $ai['sign'],
                'is_group' => MessageEnum::GROUP,
                'created_at' => $this->time
            ];
        }
        GroupUser::query()->insert($batchAiData);
        Message::query()->insert($batchMessageData);
    }

    /**
     * @throws GuzzleException
     */
    public function replyMessage(array $data): void
    {
        if (isset($this->assistant[$data['to_ai']])) {
            $ai = $this->assistant[$data['to_ai']];
            $data['type'] = $aiType = $ai['type'];

            $options = [
                'timeout' => 30,
            ];
            $client = new Client($options);
            $apiUri = $ai['api_uri'];
            $token = $ai['token'];
            $tokenType = $ai['token_type'];
            $json = [];
            if ($aiType === MessageEnum::TEXT) {
                //ai文字回复
                $json['messages'] = $ai['messages'];
                $json['messages'][] = [
                    'role' => 'user',
                    'content' => $data['content'],
                ];
            } else {
                //ai绘画
                $promptArr = explode('<>', $data['content']);
                $json = ['prompt' => $promptArr[0]];
                if (!empty($promptArr[1])) $json['negative_prompt'] = $promptArr[1];
            }
            $file = [];
            $user = User::query()->find($data['to_ai']);
            try {
                $response = $client->post($apiUri, [
                    'headers' => [
                        'Authorization' => "$tokenType $token",
                    ],
                    'json' => $json
                ]);

                if ($aiType === MessageEnum::TEXT) {
                    //回复文本信息
                    $result = json_decode($response->getBody()->getContents(), true);
                    if ($result['success']) {
                        $replyMessage = $result['result']['response'];
                    } else {
                        $replyMessage = implode('\n\n', $result['messages']);
                    }
                } else {
                    //下载并回复绘制好的图片
                    $date = date('Ymd');
                    $fileName = md5($data['content'] . uniqid(time()), true) . ".jpg";
                    $filePath = "uploads/image/{$date}/{$fileName}";
                    $fileRealPath = Storage::disk('public')->path($filePath);

//                    if (file_exists($fileRealPath)) {
//                        $signature = md5_file($fileRealPath);
//                        $file = File::query()->where('signature', $signature)->first();
//                    }
//                    if (!$file) {
                    $thumbnailFilePath = "uploads/image/{$date}/thumbnail_{$fileName}";
                    Storage::disk('public')->put($filePath, (string)$response->getBody());
                    list($width, $height, $size) = (new FileService())->makeThumbnailImage($fileRealPath, $thumbnailFilePath);
                    $file = new File();
                    $file->name = $fileName;
                    $file->path = $filePath;
                    $file->thumbnail_path = $thumbnailFilePath;
                    $file->size = $size;
                    $file->width = $width;
                    $file->height = $height;
                    $file->duration = 0;
                    $file->signature = md5_file($fileRealPath);
                    $file->type = 'image';
                    $file->format = 'jpeg';
                    $file->save();
//                    }

                    $data['extends'] = [
                        'path' => $file->path,
                        'format' => $file->format,
                        'width' => $file->width,
                        'height' => $file->height,
                        'duration' => $file->duration
                    ];
                    $data['file'] = [
                        'id' => $file->id,
                        'name' => $file->name,
                        'type' => $file->type,
                        'size' => $file->size
                    ];
                    $replyMessage = FileEnum::CONTENT[$aiType] ?? '[文件信息]';
                }
            } catch (\Exception $e) {
                $replyMessage = $e->getMessage();
            }
            DB::beginTransaction();
            try {
                //更新未读数、最新消息等信息
                if ($data['is_group'] == MessageEnum::GROUP) {
                    Group::query()->where('id', $data['to_user'])
                        ->update([
                            'send_user' => $data['to_ai'],
                            'content' => $replyMessage,
                            'time' => $this->time
                        ]);
                    GroupUser::query()
                        ->where('group_id', $data['to_user'])
                        ->update([
                            'display' => 1
                        ]);
                    GroupUser::query()
                        ->where('group_id', $data['to_user'])
                        ->where('user_id', '<>', $data['to_ai'])
                        ->increment('unread');
                } else {
                    Friend::query()
                        ->where('owner', $data['from_user'])
                        ->where('friend', $data['to_ai'])
                        ->update([
                            'display' => 1,
                            'content' => $replyMessage,
                            'time' => $this->time
                        ]);
                    Friend::query()
                        ->where('owner', $data['from_user'])
                        ->where('friend', $data['to_ai'])
                        ->increment('unread');
                }

                $messageData = [
                    'from_user' => $data['to_ai'],
                    'to_user' => $data['is_group'] == MessageEnum::GROUP ? $data['to_user'] : $data['from_user'],
                    'content' => $replyMessage,
                    'type' => $aiType,
                    'is_group' => $data['is_group'],
                    'created_at' => $this->time
                ];
                if ($aiType === MessageEnum::TEXT) {
                    $data['content'] = $replyMessage;
                } else {
                    $messageData['file_id'] = $file->id;
                    $messageData['file_name'] = $file->name;
                    $messageData['file_type'] = $file->type;
                    $messageData['file_size'] = $file->size;
                    $messageData['extends'] = json_encode($data['extends']);
                    $data['content'] = env('STATIC_FILE_URL') . '/' . $file->path;
                }
                $data['id'] = Message::query()->insertGetId($messageData);
                $data['from'] = [
                    'id' => $data['to_ai'],
                    'nickname' => $user->nickname,
                    'avatar' => $user->avatar,
                    'wechat' => $user->wechat
                ];
                $data['from_user'] = $messageData['from_user'];
                $data['to_user'] = $messageData['to_user'];

                $data['time'] = $this->time;
                $sendData = [
                    'who' => WorkerManEnum::WHO_MESSAGE,
                    'action' => WorkerManEnum::ACTION_SEND,
                    'data' => $data
                ];
                //向用户发送消息通知
                if ($data['is_group'] == MessageEnum::GROUP) {
                    $excludeClientId = Gateway::getClientIdByUid($messageData['from_user']);
                    Gateway::sendToGroup($messageData['to_user'], json_encode($sendData, JSON_UNESCAPED_UNICODE), $excludeClientId);
                } else {
                    Gateway::sendToUid($messageData['to_user'], json_encode($sendData, JSON_UNESCAPED_UNICODE));
                }
                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
            }

        }
    }
}
