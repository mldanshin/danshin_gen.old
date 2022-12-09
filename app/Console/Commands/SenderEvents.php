<?php

namespace App\Console\Commands;

use App\Services\Events\Events;
use Illuminate\Console\Command;

final class SenderEvents extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:events';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sending events to subscribers';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle(Events $events): int
    {
        $res = $events->send();
        if ($res === true) {
            $this->info('The command was successful!');
            return 1;
        } else {
            $this->error("the command failed with an error");
            return 0;
        }
    }
}
