<?php

namespace App\Console\Commands;

use App\Jobs\XmlHandlingJob;
use Illuminate\Console\Command;

class JobCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'job:insert {systemType}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Starts a job, inserts Xml data to database';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        XmlHandlingJob::dispatch($this->argument('systemType'));
        return 0;
    }
}
