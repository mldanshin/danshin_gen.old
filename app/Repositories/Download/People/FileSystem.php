<?php

namespace App\Repositories\Download\People;

use App\Repositories\Download\FileSystem as DownloadFileSystem;
use Illuminate\Contracts\Filesystem\Filesystem as FilesystemIlluminate;

final class FileSystem extends DownloadFileSystem
{
    public function __construct(private FilesystemIlluminate $disk)
    {
        parent::__construct($disk);
    }

    public function getPeoplePath(string $extension): string
    {
        return $this->path . "genealogy." . $extension;
    }

    public function getPersonPath(string $id, string $extension): string
    {
        return $this->path . "person_$id.$extension";
    }
}
