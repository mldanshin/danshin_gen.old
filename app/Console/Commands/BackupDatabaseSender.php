<?php

namespace App\Console\Commands;

use App\Backup\Database\SenderYandex as Sender;
use App\Backup\Database\Storage;
use Illuminate\Console\Command;

class BackupDatabaseSender extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'backup:db-send';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sends a database dump';

    /**
     * Execute the console command.
     */
    public function handle(Sender $sender)
    {
        $storage = new Storage();
        $storage->createDupm();

        if ($sender->send($storage->path) === true) {
            $this->info("Database dump sent successfully");
            return 0;
        } else {
            $this->error("Database dump sent error");
            return 1;
        }
    }
}
