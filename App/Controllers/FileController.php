<?php


namespace App\Controllers;

use App\Database\Models\FilesInfo;

class FileController extends BaseController
{
    public function index(): array
    {
        return FilesInfo::query()->where('user_id', '=', $this->userId)->toArray();
    }
}