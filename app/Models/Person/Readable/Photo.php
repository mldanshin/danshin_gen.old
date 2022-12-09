<?php

namespace App\Models\Person\Readable;

final class Photo
{
    public function __construct(
        private string $url,
        private string $path,
        private ?string $date
    ) {
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function getDate(): ?string
    {
        return $this->date;
    }
}
