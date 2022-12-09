<?php

namespace App\Models\People;

use App\Models\Pair;
use Illuminate\Support\Collection;

final class FilterOrdering
{
    /**
     * @param Collection|Pair[] $ordering
     */
    public function __construct(
        private string $search,
        private Collection $ordering,
        private int $orderingCurrent,
    ) {
    }

    public function getSearch(): string
    {
        return $this->search;
    }

    /**
    * @return Collection|Pair[]
    */
    public function getOrdering(): Collection
    {
        return $this->ordering;
    }

    public function getOrderingCurrent(): int
    {
        return $this->orderingCurrent;
    }
}
