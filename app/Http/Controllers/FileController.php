<?php

namespace App\Http\Controllers;

use App\Exceptions\BusinessException;
use App\Services\FileService;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class FileController extends Controller
{
    private FileService $fileService;

    public function __construct()
    {
        parent::__construct();
        $this->fileService = new FileService();
    }

    /**
     * @throws ValidationException
     * @throws BusinessException
     */
    public function upload(Request $request): \Illuminate\Http\JsonResponse
    {
        $this->validate($request, [
            'file' => 'required|file|mimes:jpg,webp,jpeg,png,gif,mp4,mov,avi,pdf,docx,txt|max:20480',
        ]);
        $data = $this->fileService->upload($request);
        return $this->success($data, $request);
    }

    /**
     * @throws ValidationException
     * @throws BusinessException
     */
    public function uploadBase64(Request $request): \Illuminate\Http\JsonResponse
    {
        $this->validate($request, [
            'base64' => 'required'
        ]);
        $data = $this->fileService->uploadBase64($this->params['base64']);
        return $this->success($data, $request);
    }
}
