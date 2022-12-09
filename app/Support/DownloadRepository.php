<?php

namespace App\Support;

use App\Repositories\Download\FileSystem;
use Illuminate\Support\Facades\File;

final class DownloadRepository
{
    private string $path;

    public function __construct()
    {
        $this->path = FileSystem::instance()->getPath();
    }

    public function clear(): bool
    {
        return File::cleanDirectory($this->path);
    }
}
