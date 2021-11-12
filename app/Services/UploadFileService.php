<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UploadFileService
{
    public function uploadMultipleFile($file, $folderName)
    {
        $allowedfileExtensionImages = ['jpg', 'png'];
        $allowedfileExtensionVideo = ['mp4', 'mov', 'ogg'];
        $allowedfileExtensionOthers = ['csv', 'txt', 'xlx', 'xls', 'pdf', 'doc', 'docx'];
        $extension = $file->getClientOriginalExtension();
        $fileName = $file->getClientOriginalName();
        $checkImage = in_array($extension, $allowedfileExtensionImages);
        $checkVideo = in_array($extension, $allowedfileExtensionVideo);
        $checkOthers = in_array($extension, $allowedfileExtensionOthers);
        if ($checkImage) {
            $dataUpload = [
                'file_name' => $fileName,
                'link' => Storage::disk('uploads')->put($folderName . '/images/'.$fileName, $file),
                'type' => 0
            ];
        } else if ($checkVideo) {
            $dataUpload = [
                'file_name' => $fileName,
                'link' => Storage::disk('uploads')->put($folderName . '/video/'.$fileName, $file),
                'type' => 1
            ];
        } else if ($checkOthers) {
            $dataUpload = [
                'file_name' => $fileName,
                'link' => Storage::disk('uploads')->put($folderName . '/others/'.$fileName, $file),
                'type' => 2
            ];
        } else {
            return response()->json(['file Error'], 400);
        }
        return $dataUpload;
    }
}
