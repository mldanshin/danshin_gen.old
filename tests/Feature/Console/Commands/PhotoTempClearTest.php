<?php

namespace Tests\Feature\Console\Commands;

use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

final class PhotoTempClearTest extends TestCase
{
    public function testSuccess(): void
    {
        $res = Artisan::call("photo:clear");
        $this->assertEquals(1, $res);
    }
}
