<?php

namespace App\Models\Person\Editable;

final class Photo
{
    public function __construct(
        private string $url,
        private string $pathRelative,
        private ?string $date,
        private int $order
    ) {
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function getPathRelative(): string
    {
        return $this->pathRelative;
    }

    public function getDate(): ?string
    {
        return $this->date;
    }

    public function getOrder(): int
    {
        return $this->order;
    }
}
