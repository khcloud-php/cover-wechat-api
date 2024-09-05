<?php

namespace App\Services;

use App\Enums\ApiCodeEnum;
use App\Exceptions\BusinessException;
use App\Models\Friend;
use App\Models\Moment;
use App\Models\MomentFiles;
use Illuminate\Support\Facades\DB;

class MomentService extends BaseService
{
    public function list(array $params): array
    {
        $owner = $params['user']->id;
        $page = $params['page'] ?? 1;
        $limit = $params['limit'] ?? 10;
        $friends = Friend::getMomentCanSeeFriends($owner);
        $friendIds = array_keys($friends);
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

    public function delete()
    {

    }
}
