<?php

namespace App\Services;

use App\Enums\ApiCodeEnum;
use App\Exceptions\BusinessException;
use App\Models\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class FileService extends BaseService
{
    /**
     * @throws BusinessException
     */
    public function upload(Request $request): array
    {
        //上传的如果是头像就需要裁剪成正方头像
        $avatar = $request->input('avatar');
        $file = $request->file('file');
        if (!$file->isValid()) {
            $this->throwBusinessException(ApiCodeEnum::CLIENT_PARAMETER_ERROR);
        }
        // 已存在直接返回
        $realPath = $file->getPathname();
        $signature = md5_file($realPath);
        if ($data = $this->checkFileExists($signature)) {
            return $data;
        }
        // 生成新的文件名
        $newFileName = $signature . '.' . $file->getClientOriginalExtension();
        $newThumbnailFileName = 'thumbnail_' . $newFileName;
        // 获取文件信息
        $size = $file->getSize();
        $mimeTypeArr = explode('/', $file->getClientMimeType());
        $fileType = $mimeTypeArr[0] ?? 'file';
        $fileFormat = $mimeTypeArr[1] ?? $file->getClientOriginalExtension();
        // 初始化变量
        $width = 0;
        $height = 0;
        $duration = 0;
        $date = date('Ymd');
        $filePath = "uploads/image/{$date}/{$newFileName}";
        $outputPath = Storage::disk('public')->path($filePath);
        $thumbnailPath = '';
        $needCropSquare = false;
        //头像的话需要截图
        if ($avatar) {
            $needCropSquare = $this->cropSquare($realPath, $outputPath);
        }

        // 指定上传路径并重命名文件
        if (!$needCropSquare)
            $path = $file->storeAs("uploads/{$fileType}/{$date}", $newFileName, 'public');
        else {
            $realPath = $outputPath;
            $path = $filePath;
        }

        if ($fileType == 'image') {
            // 生成缩略图
            $thumbnailPath = "uploads/{$fileType}/{$date}/{$newThumbnailFileName}";
            list($width, $height, $size) = $this->makeThumbnailImage($realPath, $thumbnailPath);
        } elseif ($fileType == 'video') {
            // 获取视频时长和封面图
            $ffmpeg = \FFMpeg\FFMpeg::create();
            $video = $ffmpeg->open($realPath);
            $ffprobe = \FFMpeg\FFProbe::create();
            $duration = (int)$ffprobe->format($realPath)->get('duration');
            $fileType = 'video';
            $thumbnailPath = "uploads/{$fileType}/{$date}/{$newThumbnailFileName}";
            $video->frame(\FFMpeg\Coordinate\TimeCode::fromSeconds(1))
                ->save(storage_path('app/public/' . $thumbnailPath));
        }

        // 将文件信息存储到数据库
        $fileRecord = new File();
        $fileRecord->name = $newFileName;
        $fileRecord->path = $path;
        $fileRecord->thumbnail_path = $thumbnailPath;
        $fileRecord->size = $size;
        $fileRecord->width = $width;
        $fileRecord->height = $height;
        $fileRecord->duration = $duration;
        $fileRecord->signature = $signature;
        $fileRecord->type = $fileType;
        $fileRecord->format = $fileFormat;
        $fileRecord->save();

        // 获取文件的 URL
        $data = $fileRecord->toArray();
        $data['url'] = env('STATIC_FILE_URL') . '/' . $path;
        $data['thumbnail_url'] = env('STATIC_FILE_URL') . '/' . $thumbnailPath;

        return $data;
    }

    /**
     * @throws BusinessException
     */
    public function uploadBase64(string $base64Image)
    {
        // 解析 Base64 编码的图像数据
        list($type, $data) = explode(';', $base64Image);
        list(, $data) = explode(',', $data);
        $imageData = base64_decode($data);
        $date = date('Ymd');
        // 确定文件扩展名
        $mimeType = explode(':', explode(';', $base64Image)[0])[1];
        $extension = match ($mimeType) {
            'image/jpeg' => 'jpeg',
            'image/png' => 'png',
            default => $this->throwBusinessException(ApiCodeEnum::CLIENT_PARAMETER_ERROR),
        };
        // 生成唯一文件名
        $fileName = uniqid() . '.' . $extension;
        $filePath = "uploads/image/{$date}/{$fileName}";
        Storage::disk('public')->put($filePath, $imageData);
        $fileRealPath = Storage::disk('public')->path($filePath);
        $signature = md5_file($fileRealPath);
        if ($data = $this->checkFileExists($signature)) {
            @unlink($fileRealPath);
            return $data;
        }
        $size = Storage::disk('public')->size($filePath);
        // 生成缩略图
        $thumbnailFilePath = "uploads/image/{$date}/thumbnail_{$fileName}";
        list($width, $height) = $this->makeThumbnailImage($fileRealPath, $thumbnailFilePath);
        // 将文件信息存储到数据库
        $fileRecord = new File();
        $fileRecord->name = $fileName;
        $fileRecord->path = $filePath;
        $fileRecord->thumbnail_path = $thumbnailFilePath;
        $fileRecord->size = $size;
        $fileRecord->width = $width;
        $fileRecord->height = $height;
        $fileRecord->duration = 0;
        $fileRecord->signature = $signature;
        $fileRecord->type = 'image';
        $fileRecord->format = $extension;
        $fileRecord->save();
        // 获取文件的 URL
        $data = $fileRecord->toArray();
        $data['url'] = env('STATIC_FILE_URL') . '/' . $filePath;
        $data['thumbnail_url'] = env('STATIC_FILE_URL') . '/' . $thumbnailFilePath;

        return $data;
    }

    private function checkFileExists(string $signature): bool|array
    {
        $file = File::query()->where('signature', $signature)->first();
        if ($file) {
            $data = $file->toArray();
            $data['url'] = env('STATIC_FILE_URL') . '/' . $file->path;
            $data['thumbnail_url'] = env('STATIC_FILE_URL') . '/' . $file->thumbnail_path;
            return $data;
        }
        return false;
    }

    /**
     * @throws BusinessException
     */
    private function makeThumbnailImage(string $filePath, string $thumbnailFilePath): array
    {
        if (!file_exists($filePath)) $this->throwBusinessException(ApiCodeEnum::SYSTEM_ERROR, '生成缩略图失败，文件不存在');
        $image = Image::make($filePath);
        $width = $image->width();
        $height = $image->height();
        $size = $image->filesize();
        // 生成缩略图
        $thumbnail = $image->resize($width * 0.5, $height * 0.5, function ($constraint) {
            $constraint->aspectRatio();
        });
        Storage::disk('public')->put($thumbnailFilePath, (string)$thumbnail->encode());
        return [$width, $height, $size];
    }

    /**
     * @throws BusinessException
     */
    private function cropSquare(string $filePath, string $outputPath): bool
    {
        if (!file_exists($filePath)) $this->throwBusinessException(ApiCodeEnum::SYSTEM_ERROR, '裁剪图片失败，文件不存在');
        // 创建一个 Image 实例
        $image = Image::make($filePath);
        // 获取图片的宽和高
        $width = $image->width();
        $height = $image->height();
        if ($width == $height) return false;
        // 计算正方形的边长
        $sideLength = min($width, $height);
        // 计算裁剪位置
        $x = $width > $height ? intval(($width - $sideLength) / 2) : 0;
        $y = $height > $width ? intval(($height - $sideLength) / 2) : 0;
        // 居中裁剪成正方形
        $image->crop($sideLength, $sideLength, $x, $y);
        // 保存裁剪后的图片
        $image->save($outputPath);
        return true;
    }
}
