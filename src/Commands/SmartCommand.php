<?php

namespace Dietercoopman\Smart\Commands;

use Illuminate\Console\Command;

class SmartCommand extends Command
{
    public $signature = 'skeleton';

    public $description = 'My command';

    public function handle()
    {
        $this->comment('All done');
    }
}
