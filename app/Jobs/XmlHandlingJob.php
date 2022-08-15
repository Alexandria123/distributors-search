<?php

namespace App\Jobs;

use App\Distributors\Distributors;
use App\Models\Distributor;
use App\Repository\XmlFileRepository;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class XmlHandlingJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private Distributors $distributorsInsert;
    private string $systemType;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($systemType)
    {
        $this->distributorsInsert = new Distributors();
        $this->systemType = $systemType;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
       $this->distributorsInsert->insertToDBXmlData($this->systemType);
    }
}
