<?php

namespace App\Models\Tree;

final class Tree
{
    public function __construct(
        private PersonShort $personTarget,
        private Family $family
    ) {
    }

    public function getPersonTarget(): PersonShort
    {
        return $this->personTarget;
    }

    public function getFamily(): Family
    {
        return $this->family;
    }
}
