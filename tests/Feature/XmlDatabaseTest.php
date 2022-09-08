<?php

namespace Tests\Feature;

use App\Distributors\AllDistributors;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class XmlDatabaseTest extends TestCase
{
    use RefreshDatabase;

    private \SimpleXMLElement $xmlFile;
    private AllDistributors $distributors;

    public function setUp(): void
    {
        parent::setUp();
        $xml = file_get_contents(__DIR__."/Fixtures/xmlFileDataTest.xml");
        $this->distributors = new AllDistributors();
        Storage::disk('local')->put('xmlFIleData.xml', $xml);
        $this->xmlFile = simplexml_load_string(Storage::disk('local')->get('xmlFIleData.xml'));

    }

    public function test_distributors(): void
    {
        $this->artisan('startJob:insertToDB kodeks')->assertExitCode(0);
        Storage::disk('local')->assertExists('xmlFIleData.xml');
        $arrayDistributors = $this->distributors->getAllDistributorsPrepared($this->xmlFile);
        foreach ($arrayDistributors as $distributor){
            $this->assertDatabaseHas('distributors', [
                'name' => $distributor['name'],
                'domains' => $this->castAsJson($distributor['domains']),
                'emails' => $this->castAsJson($distributor['emails']),
                'address' => $distributor['address'],
                'phone' => $distributor['phone'],
            ]);
        }
    }

    public function test_cities(){
        $this->artisan('startJob:insertToDB kodeks')->assertExitCode(0);
        $arrayDistributors = $this->distributors->getAllDistributorsPrepared($this->xmlFile);
        foreach ($arrayDistributors as $distributor){
            if($distributor['city'] != "") {
                $this->assertDatabaseHas('cities', [
                    'name' => $distributor['city']
                ]);
            }
        }
    }

    public function test_regions(){
        $this->artisan('startJob:insertToDB kodeks')->assertExitCode(0);
        $arrayDistributors = $this->distributors->getAllDistributorsPrepared($this->xmlFile);
        foreach($arrayDistributors as $distributor){
            $this->assertDatabaseHas('regions', [
                'name' => $distributor['regname']
            ]);
        }
    }

}
