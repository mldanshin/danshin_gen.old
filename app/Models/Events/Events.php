<?php

namespace App\Models\Events;

use Illuminate\Support\Collection;

final class Events
{
    /**
     * @param Collection|Event[] $past
     * @param Collection|Event[] $today
     * @param Collection|Event[] $nearest
     */
    public function __construct(
        private Collection $past,
        private Collection $today,
        private Collection $nearest
    ) {
    }

    /**
     * @return Collection|Event[]
     */
    public function getPast(): Collection
    {
        return $this->past;
    }

    /**
     * @return Collection|Event[]
     */
    public function getToday(): Collection
    {
        return $this->today;
    }

    /**
     * @return Collection|Event[]
     */
    public function getNearest(): Collection
    {
        return $this->nearest;
    }

    public function isEmpty(): bool
    {
        if ($this->past->isEmpty() && $this->today->isEmpty() && $this->nearest->isEmpty()) {
            return true;
        } else {
            return false;
        }
    }
}
