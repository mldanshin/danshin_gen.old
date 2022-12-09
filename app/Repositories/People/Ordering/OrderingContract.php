<?php

namespace App\Repositories\People\Ordering;

use App\Models\PersonShort as PersonModel;

interface OrderingContract
{
    /**
     * @param PersonModel[] $person
     */
    public function sort(array &$person): void;
}
