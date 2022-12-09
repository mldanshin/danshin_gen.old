<?php

namespace App\Console\Commands;

use App\Backup\Database\Storage as DatabaseStorage;
use Illuminate\Console\Command;

class BackupDatabaseCreator extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'backup:db-create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates a database dump and sends the file to storage';

    /**
     * Execute the console command.
     */
    public function handle(DatabaseStorage $storage)
    {
        $storage->createDupm();

        $this->info("Dump database successfully created");

        return 0;
    }
}
