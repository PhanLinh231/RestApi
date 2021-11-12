<?php

namespace App\Repositories;

use App\Models\User;
use App\Models\UserFiles;
use App\Repositories\Contracts\UserUploadRepositoryContract;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class UserUploadRepository implements UserUploadRepositoryContract
{
    public function uploadImage($file)
    {
        try {
            $newEntry = new UserFiles();
            $newEntry->status_id = auth()->user()->id;
            $newEntry->link = url(Storage::url($file));
            $newEntry->save();

            return true;
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
}
