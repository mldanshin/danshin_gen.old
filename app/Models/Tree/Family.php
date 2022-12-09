<?php

namespace App\Models\Tree;

use Illuminate\Support\Collection;

final class Family
{
    /**
     * @param Collection|Person[] $marriage
     * @param Collection|Family[] $childrens
     */
    public function __construct(
        private Person $person,
        private Collection $marriage,
        private Collection $childrens
    ) {
    }

    public function getPerson(): Person
    {
        return $this->person;
    }

    /**
     * @return Collection|Person[]
     */
    public function getMarriage(): Collection
    {
        return $this->marriage;
    }

    /**
     * @return Collection|Family[]
     */
    public function getChildrens(): Collection
    {
        return $this->childrens;
    }
}
