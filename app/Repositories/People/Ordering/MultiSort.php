<?php

namespace App\Repositories\People\Ordering;

trait MultiSort
{
    /**
     * @param string[] $fields
     */
    private function createCallbackCompare(array $fields): callable
    {
        return function ($person1, $person2) use ($fields) {
            $i = 0;
            $c = count($fields);
            $cmp = 0;
            while ($cmp == 0 && $i < $c) {
                $cmp = strcmp($person1->{$fields[$i]}(), $person2->{$fields[$i]}());
                $i++;
            }

            return $cmp;
        };
    }
}
