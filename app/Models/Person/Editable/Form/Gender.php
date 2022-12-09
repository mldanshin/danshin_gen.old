<?php

namespace App\Models\Person\Editable\Form;

use App\Models\Pair;
use Illuminate\Support\Collection;

final class Gender
{
    /**
     * @param Collection|Pair[] $options
     */
    public function __construct(
        private Collection $options,
        private int $type,
    ) {
    }

    /**
     * @return Collection|Pair[]
     */
    public function getOptions(): Collection
    {
        return $this->options;
    }

    public function getType(): int
    {
        return $this->type;
    }
}
