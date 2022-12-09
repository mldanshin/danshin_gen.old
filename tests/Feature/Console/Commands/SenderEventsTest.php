<?php

namespace Tests\Feature\Console\Commands;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

final class SenderEventsTest extends TestCase
{
    use RefreshDatabase;

    public function testSuccess(): void
    {
        $this->seed();

        $res = Artisan::call("send:events");
        $this->assertEquals(1, $res);
    }
}
