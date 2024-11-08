<?php

namespace App\Services;

use App\Enums\ApiCodeEnum;
use App\Enums\WorkerManEnum;
use App\Exceptions\BusinessException;
use App\Models\Friend;
use App\Models\Moment;
use App\Models\MomentComments;
use App\Models\MomentFiles;
use App\Models\MomentLikes;
use App\Models\User;
use GatewayWorker\Lib\Gateway;
use Illuminate\Support\Facades\DB;

class MomentService extends BaseService
{
    public function list(array $params): array
    {
        $owner = $params['user']->id;
        $page = $params['page'] ?? 1;
        $limit = $params['limit'] ?? 10;
        $friendIds = Friend::getMomentCanSeeFriendIds($owner);
        return Moment::getMomentsPageByUserIds($friendIds, $owner, $page, $limit);
    }

    /**
     * @throws BusinessException
     */
    public function publish(array $params): array
    {
        $momentData = [
            'user_id' => $params['user']->id,
            'type' => $params['type'],
            'content' => $params['content'],
            'created_at' => time(),
        ];
        DB::beginTransaction();
        try {
            $id = Moment::query()->insertGetId($momentData);
            if (!empty($params['files'])) {
                $fileService = new FileService();
                $batchFileData = [];
                foreach ($params['files'] as $file) {
                    $file = $fileService->uploadFile($file, false);
                    $batchFileData[] = [
                        'moment_id' => $id,
                        'file_id' => $file['id'],
                        'created_at' => time(),
                    ];
                }
                MomentFiles::query()->insert($batchFileData);
            }
            DB::commit();
            return ['id' => $id];
        } catch (\Exception $e) {
            DB::rollBack();
            $this->throwBusinessException(ApiCodeEnum::SYSTEM_ERROR, $e->getMessage());
        }
    }

    /**
     * @throws BusinessException
     */
    public function like(array $params): array
    {
        $moment = $this->getMomentById($params['id']);
        $likeData = [
            'moment_id' => $params['id'],
            'user_id' => $params['user']->id,
            'created_at' => time(),
        ];
        DB::beginTransaction();
        try {
            $likeData['id'] = MomentLikes::query()->insertGetId($likeData);
            $likeData['user'] = [
                'id' => $params['user']->id,
                'nickname' => $params['user']->nickname,
                'avatar' => $params['user']->avatar
            ];
            $sendData = [
                'who' => WorkerManEnum::WHO_MOMENT,
                'action' => WorkerManEnum::ACTION_LIKE,
                'data' => $likeData
            ];
            //获取并通知有评论或点赞过这条朋友圈的共同好友
            $noticePublicFriendIds = Moment::getNoticePublicFriendIds($params['id'], $params['user']->id, $moment->user_id);
            User::incrUnread($noticePublicFriendIds, 'moment.num', $params['user']->id);
            foreach ($noticePublicFriendIds as $friendId) {
                Gateway::sendToUid($friendId, json_encode($sendData, JSON_UNESCAPED_UNICODE));
            }
            DB::commit();
            return $likeData;
        } catch (\Exception $e) {
            DB::rollBack();
            $this->throwBusinessException(ApiCodeEnum::SYSTEM_ERROR, $e->getMessage());
        }
    }

    /**
     * @throws BusinessException
     */
    public function unlike(array $params): array
    {
        $like = MomentLikes::query()->where('moment_id', $params['id'])
            ->where('user_id', $params['user']->id)->first();
        if (!$like) {
            $this->throwBusinessException(ApiCodeEnum::CLIENT_PARAMETER_ERROR);
        }
        $like->delete();
        return ['moment_id' => $params['id'], 'like_id' => $like->id];
    }

    /**
     * @throws BusinessException
     */
    public function comment(array $params)
    {
        $moment = $this->getMomentById($params['id']);
        $fromUser = $params['user']->id;
        $toUser = $params['to_user'];
        $content = $params['content'];
        $commentData = [
            'moment_id' => $params['id'],
            'from_user' => $fromUser,
            'to_user' => $toUser,
            'content' => $content,
            'created_at' => time(),
        ];
        DB::beginTransaction();
        try {
            $commentData['id'] = MomentComments::query()->insertGetId($commentData);
            $commentData['from'] = [
                'id' => $params['user']->id,
                'nickname' => $params['user']->nickname,
                'avatar' => $params['user']->avatar,
                'wechat' => $params['user']->wechat
            ];
            $commentData['to'] = [];
            if ($toUser) {
                $user = User::query()->find($toUser);
                $commentData['to'] = [
                    'id' => $user->id,
                    'nickname' => $user->nickname,
                    'avatar' => $user->avatar,
                    'wechat' => $user->wechat
                ];
            }
            $sendData = [
                'who' => WorkerManEnum::WHO_MOMENT,
                'action' => WorkerManEnum::ACTION_COMMENT,
                'data' => $commentData
            ];


            //获取并通知有评论或点赞过这条朋友圈的共同好友
            $noticePublicFriendIds = Moment::getNoticePublicFriendIds($params['id'], $fromUser, $moment->user_id, $toUser);
            User::incrUnread($noticePublicFriendIds, 'moment.num', $fromUser);
            foreach ($noticePublicFriendIds as $friendId) {
                Gateway::sendToUid($friendId, json_encode($sendData, JSON_UNESCAPED_UNICODE));
            }
            DB::commit();
            return $commentData;
        } catch (\Exception $e) {
            DB::rollBack();
            $this->throwBusinessException(ApiCodeEnum::SYSTEM_ERROR, $e->getMessage());
        }
    }

    public function unread(array $params): array
    {
        $userId = $params['user']->id;
        $unread = User::getUnreadById($userId);
        $moment = $unread['moment'];
        $from = [];
        if ($moment['num'] > 0) {
            $from = User::query()->find($moment['from'], ['id', 'nickname', 'avatar', 'wechat']);
        }
        return ['cnt' => $moment['num'], 'user' => $from];
    }

    public function unreadList(array $params): array
    {
        $unreadLikes = MomentLikes::getUnreadLikesByUserId($params['user']->id);
        $unreadComments = MomentComments::getUnreadCommentsByUserId($params['user']->id);
        DB::beginTransaction();
        try {
            if ($unreadLikes) {
                $momentIds = array_column($unreadLikes, 'moment_id');
                MomentLikes::query()->whereIn('moment_id', $momentIds)->update(['is_read' => 1]);
            }
            if ($unreadComments) {
                $momentIds = array_column($unreadComments, 'moment_id');
                MomentComments::query()->whereIn('moment_id', $momentIds)->update(['is_read' => 1]);
            }
            DB::commit();
        } catch (\Exception) {
            DB::rollBack();
        }
        $unreadList = array_merge($unreadLikes, $unreadComments);
        usort($unreadList, function ($a, $b) {
            return $b['created_at'] <=> $a['created_at'];
        });
        return $unreadList;
    }

    /**
     * @throws BusinessException
     */
    public function delete(array $params): array
    {
        $moment = $this->getMomentById($params['id']);
        if ($params['user']->id != $moment->user_id) {
            $this->throwBusinessException(ApiCodeEnum::CLIENT_PARAMETER_ERROR);
        }
        Moment::query()->where('id', $params['id'])->delete();
        return ['id' => $params['id']];
    }

    /**
     * @throws BusinessException
     */
    private function getMomentById(int $id): \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Builder|array|null
    {
        $moment = Moment::query()->find($id);
        if (!$moment) {
            $this->throwBusinessException(ApiCodeEnum::CLIENT_PARAMETER_ERROR);
        }
        return $moment;
    }
}
