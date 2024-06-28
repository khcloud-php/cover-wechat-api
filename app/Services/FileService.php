<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;

class FileService extends BaseService
{
    public function upload($file)
    {
        if (!$file->isValid()) {
            return response()->json(['error' => 'Invalid file upload'], 400);
        }
        $mimeType = $file->getClientMimeType();
        $originalName = $file->getClientOriginalName();
        $extension = $file->getClientOriginalExtension();
        $newName = md5($originalName . microtime()) . '.' . $extension;
        $mimeTypeArr = explode('/', $mimeType);
        $dirName = array_shift($mimeTypeArr);
        if (!in_array($dirName, ['image', 'video'])) {
            $dirName = "file";
        }

        $dir = $dirName . '/' . date('Ymd');

        // 确保使用 Storage facade，而非直接文件操作
        $path = Storage::disk('public')->putFileAs($dir, $file, $newName);

//        if ($path) {
//            return response()->json(['data' => env('STATIC_FILE_URL').'/'.$path]);
//        } else {
//            return response()->json(['error' => 'Failed to upload file'], 500);
//        }
    }
}
