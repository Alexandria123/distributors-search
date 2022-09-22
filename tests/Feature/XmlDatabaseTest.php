<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class XmlDatabaseTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        Storage::fake('local');
        $xml = file_get_contents(__DIR__."/Fixtures/xmlFileDataTest.xml");
        Storage::disk('local')->put('systemXml/centers_kodeks.xml', $xml);
    }

    public function testShouldAssertDbDistributorsHasXml(): void
    {
        $this->artisan('distrs:update-db kodeks')->assertExitCode(0);

        $this->assertDatabaseHas('distributors', [
            'name' => 'ИП Зубанова Юлия Александровна',
            'emails' => $this->castAsJson(['cntd-22@yandex.ru']),
            'address' => '656015, г. Барнаул, пр-т Красноармейский, 108, оф. 302',
            'phone' => '+7 (3852) 53 24 00, +7 (913) 215 55 70',
            'status' => 'Дистрибьютор'
        ]);
        $this->assertDatabaseHas('distributors', [
            'name' => "Индивидуальный предприниматель Сочугова Наталья Ивановна",
            'emails' => $this->castAsJson(['cntd3000@yandex.ru']),
            'address' => '656015, г. Барнаул, пр-т Красноармейский, 108, оф.302',
            'phone' => '+7 (913) 214 69 13',
            'status' => 'Дистрибьютор'
        ]);
    }

    public function testShouldAssertDbCitiesHasXml(){
       $this->artisan('distrs:update-db kodeks')->assertExitCode(0);
        $this->assertDatabaseHas('cities', [
            'name' => 'г. Барнаул'
        ]);
    }

    public function testShouldAssertDbRegionsHasXml(){
        $this->artisan('distrs:update-db kodeks')->assertExitCode(0);
        $this->assertDatabaseHas('regions', [
            'name' => 'Азербайджанская Республика (Азербайджан)'
        ]);
        $this->assertDatabaseHas('regions', [
            'name' => 'Алтайский край'
        ]);
    }
}
