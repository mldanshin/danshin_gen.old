<?php

namespace App\Repositories\Download\Photo;

use App\Models\Download\Photo\FileArchive as File;
use App\Repositories\Download\FileSystem;
use App\Repositories\Person\PhotoFileSystem;

final class Download
{
    private FileSystem $fileSystem;
    private PhotoFileSystem $photoFileSystem;

    /**
     * @var array<File> $files
     */
    private array $files;

    private ?string $path = null;

    public function __construct(?FileSystem $fileSystem = null, ?PhotoFileSystem $photoFileSystem = null)
    {
        $this->initializeFileSystem($fileSystem);
        $this->initializePhotoFileSystem($photoFileSystem);
        $this->initializeFiles();
        $this->initializePath();
        $this->createFile();
    }

    public function getPath(): ?string
    {
        return $this->path;
    }

    private function initializeFileSystem(?FileSystem $fileSystem): void
    {
        if ($fileSystem === null) {
            $this->fileSystem = FileSystem::instance();
        } else {
            $this->fileSystem = $fileSystem;
        }
    }

    private function initializePhotoFileSystem(?PhotoFileSystem $photoFileSystem): void
    {
        if ($photoFileSystem === null) {
            $this->photoFileSystem = PhotoFileSystem::instance();
        } else {
            $this->photoFileSystem = $photoFileSystem;
        } 
    }

    private function initializeFiles(): void
    {
        $this->files = $this->photoFileSystem->getFilesArchive();
    }

    private function initializePath(): void
    {
        if (count($this->files) > 0) {
            $this->path = $this->fileSystem->getPath() . "photo-genealogy.zip";
        }
    }

    private function createFile(): void
    {
        if ($this->path === null) {
            return;
        }

        $zip = new \ZipArchive();
        if (file_exists($this->path)) {
            $zip->open($this->path, \ZipArchive::OVERWRITE);
        } else {
            $zip->open($this->path, \ZipArchive::CREATE);
        }

        foreach ($this->files as $file) {
            $zip->addFile($file->getPath(), $file->getEntryName());
        }

        $zip->close();
    }
}
