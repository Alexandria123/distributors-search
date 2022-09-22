<?php

namespace App\Jobs;

use App\Distributors\XmlInsert;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class XmlHandlingJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private string $systemType;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($systemType)
    {
        $this->systemType = $systemType;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(): void
    {
        (new XmlInsert())->insertToDBXmlData($this->systemType);
    }
}
