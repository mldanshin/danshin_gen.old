<?php

namespace Tests;

use Database\Seeders\DatabaseSeederTesting;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    /**
     * @param  array<string>|string  $class
     * @return $this
     */
    public function seed($class = DatabaseSeederTesting::class)
    {
        parent::seed($class);

        return $this;
    }
}
