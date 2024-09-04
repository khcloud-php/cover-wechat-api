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
        $friends = Friend::getMomentCanSeeFriends($owner);
        $friendIds = array_keys($friends);
        $moments = Moment::getMomentsByUserIds($friendIds, $owner);
        usort($moments, function ($a, $b) {
           return $b['created_at'] - $a['created_at'];
        });
        return $moments;
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
                    $file = $fileService->uploadFile($file, []);
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
