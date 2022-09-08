<?php

namespace Tests\Feature;

use App\Distributors\AllDistributors;
use App\Repository\XmlFileRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Mockery\MockInterface;
use PHPUnit\Util\Test;
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

    //Проверяет job задачу, что таблицы бд заполнены данными из файла xml
//    public function test_job_console(): void
//    {
//        //Что команда выполняется успешно
//        $this->artisan('startJob:insertToDB kodeks')->assertExitCode(0);
//        $xmlRepository = new XmlFileRepository();
//        $distributors = new AllDistributors();
//        $arrayDistributors = $distributors->getAllDistributorsPrepared($xmlRepository->getXmlFileBySystemType('kodeks'));
//        //Storage::fake('xmlFile');
//        //Обход подготовленного массива xml
//        //Проверяет есть ли в бд данные из xml файла
//
//    }

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
