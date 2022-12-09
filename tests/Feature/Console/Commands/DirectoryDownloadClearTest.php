<?php

namespace Tests\Feature\Console\Commands;

use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

final class DirectoryDownloadClearTest extends TestCase
{
    public function testSuccess(): void
    {
        $res = Artisan::call("download:clear");
        $this->assertEquals(1, $res);
    }
}
