<?php

namespace Tests\DataProvider;

trait Storage
{
    private function setConfigFakeDisk(): void
    {
        config(["filesystems.disks.public.root" => storage_path('framework/testing/disks/public')]);
    }
}
