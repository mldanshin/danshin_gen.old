<?php

namespace App\Repositories\Download;

use Illuminate\Contracts\Filesystem\Filesystem as FilesystemIlluminate;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class FileSystem
{
    protected const PATH_RELATIVE = "download/";
    protected string $path;

    public function __construct(private FilesystemIlluminate $disk)
    {
        $this->path = $disk->path(self::PATH_RELATIVE);
        $this->createPath();
    }

    public static function instance(): static
    {
        return new static(Storage::disk("public"));
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function getDisk(): FilesystemIlluminate
    {
        return $this->disk;
    }

    private function createPath(): void
    {
        if (!File::exists($this->path)) {
            File::makeDirectory($this->path);
            File::chmod($this->path, 0777);
        }
    }
}
