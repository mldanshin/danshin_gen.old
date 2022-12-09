<?php

namespace App\Models\Person\Editable;

final class Internet
{
    public function __construct(
        private string $url,
        private string $name
    ) {
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function getName(): string
    {
        return $this->name;
    }
}
