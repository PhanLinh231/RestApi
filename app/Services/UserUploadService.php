<?php

namespace App\Services;

use App\Repositories\Contracts\UserUploadRepositoryContract;
use Illuminate\Support\Facades\Auth;

class UserUploadService
{
    protected $userUploadRepositoryContract;

    public function __construct(UserUploadRepositoryContract $userUploadRepositoryContract)
    {
        $this->userUploadRepositoryContract = $userUploadRepositoryContract;
    }

    public function uploadImage($file)
    {
        return $this->userUploadRepositoryContract->uploadImage($file);
    }

}
