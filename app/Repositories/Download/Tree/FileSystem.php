<?php

namespace App\Repositories\Download\Tree;

use App\Repositories\Download\FileSystem as DownloadFileSystem;
use Illuminate\Contracts\Filesystem\Filesystem as FilesystemIlluminate;
use Illuminate\Support\Facades\File;

final class FileSystem extends DownloadFileSystem
{
    public function __construct(private FilesystemIlluminate $disk)
    {
        parent::__construct($disk);
    }

    /**
     * @throws \Exception
     */
    public function createFile(string $id, ?string $parentId, string $content): string
    {
        $pathFile = $this->generateFilePath($id, $parentId);
        try {
            if (File::put($pathFile, $content) !== false) {
                return $pathFile;
            } else {
                throw new \Exception("failed to write file, id=$id, parentId=$parentId");
            }
        } catch (\Exception) {
            throw new \Exception("failed to write file, id=$id, parentId=$parentId");
        } catch (\Error) {
            throw new \Exception("failed to write file, id=$id, parentId=$parentId");
        }
    }

    private function generateFilePath(string $id, ?string $parentId): string
    {
        $pathFile = $this->path . "tree_{$id}";
        if ($parentId !== null) {
            $pathFile .= "_$parentId";
        }
        return $pathFile . ".svg";
    }
}
