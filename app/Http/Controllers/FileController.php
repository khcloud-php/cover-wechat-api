<?php

namespace App\Http\Controllers;

use App\Services\FileService;
use Illuminate\Http\Request;

class FileController extends Controller
{
    private FileService $fileService;

    public function __construct()
    {
        parent::__construct();
        $this->fileService = new FileService();
    }

    public function upload(Request $request)
    {
        $this->validate($request, [
            'file' => 'required|file|mimes:jpeg,jpg,png,gif,mp4,avi,wmv,mpeg'
        ]);
        $file = $request->file('file');
        $file->getFileInfo();
        $data = $this->fileService->upload($file);
        $this->success($data, $request);
    }
}
