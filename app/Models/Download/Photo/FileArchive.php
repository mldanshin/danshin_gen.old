<?php

namespace App\Models\Download\Photo;

final class FileArchive
{
    public function __construct(private string $path, private string $entryName)
    {
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function getEntryName(): string
    {
        return $this->entryName;
    }
}
