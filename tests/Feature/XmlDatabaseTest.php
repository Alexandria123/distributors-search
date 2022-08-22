<?php

namespace Tests\Feature;

use App\Distributors\AllDistributors;
use App\Repository\XmlFileRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class XmlDatabaseTest extends TestCase
{
   use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    //Проверяет job задачу, что таблицы бд заполнены данными из файла xml
    public function test_job_console(){
        //Что команда выполняется успешно
        $this->artisan('job:insert kodeks')->assertExitCode(0);
        $xmlRepository = new XmlFileRepository();
        $distributors = new AllDistributors();
        $arrayDistributors= $distributors->getAllDistributorsPrepared($xmlRepository->getXmlFileBySystemType('kodeks'));
        //Обход подготовленного массива xml
        //Проверяет есть ли в бд данные из xml файла
        foreach ($arrayDistributors as $distributor)
        {
            $this->assertDatabaseHas('distributors', [
                'name' => $distributor['name'],
                'email' => $this->castAsJson($distributor['email']),
                'domain' => $this->castAsJson($distributor['domain']),
                'address' => $distributor['address'],
                'phone' => $distributor['phone']
            ]);
            //Проверяем заполненность городов данными xml, пропускаем пустые значения города xml
            if($distributor['city']!="") {
                $this->assertDatabaseHas('cities', [
                    'name' => $distributor['city']
                ]);
            }
            //Проверяем заполненность регионов
            $this->assertDatabaseHas('regions', [
                'name' => $distributor['regname']
            ]);
        }
    }
//    public function test_db()
//    {
//        $this->seed();
//    }
}
