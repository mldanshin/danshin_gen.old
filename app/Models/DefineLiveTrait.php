<?php

namespace App\Models;

trait DefineLiveTrait
{
    private function getLive(?string $deathDate): bool
    {
        if ($deathDate === null) {
            return true;
        } else {
            return false;
        }
    }
}
