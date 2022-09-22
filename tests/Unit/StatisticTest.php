<?php

namespace Tests\Unit;

use App\Distributors\Statistics;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class StatisticTest extends TestCase
{
    use RefreshDatabase;
    private Statistics $statistics;

    public function setUp(): void
    {
        parent::setUp();
        Storage::fake('local');
        $xml = file_get_contents("tests/Feature/Fixtures/xmlFileDataTest.xml");
        Storage::disk('local')->put('systemXml/centers_kodeks.xml', $xml);
    }

    public function testShouldAssertEqualResultWithExpectedAndLeastDistributors()
    {
        $this->artisan('distrs:update-db kodeks')->assertExitCode(0);
        $this->statistics = new Statistics();
        $leastDistrs = $this->statistics->getRegionsWhereLeastDistributors();
        $this->assertEquals(['name'=>"Архангельская обл.", 'count'=>1], $leastDistrs[2]);
        $this->assertEquals(['name'=>"Алтайский край", 'count'=>2], $leastDistrs[1]);
        $this->assertEquals(['name'=>"Воронежская обл.", 'count'=>3], $leastDistrs[0]);
    }

    public function testShouldAssertEqualResultWithExpectedAndMostDistributors()
    {
        $this->artisan('distrs:update-db kodeks')->assertExitCode(0);
        $this->statistics = new Statistics();
        $mostDistrs = $this->statistics->getRegionsWhereMostDistributors();
        $this->assertEquals(['name'=>"Воронежская обл.", 'count'=>3], $mostDistrs[0]);
        $this->assertEquals(['name'=>"Алтайский край", 'count'=>2], $mostDistrs[1]);
        $this->assertEquals(['name'=>"Архангельская обл.", 'count'=>1], $mostDistrs[2]);
    }

    public function testShouldAssertEqualResultWithExpectedAndWhereNoDistributors(){
        $this->artisan('distrs:update-db kodeks')->assertExitCode(0);
        $this->statistics = new Statistics();
        $this->assertEquals(['name'=>"Азербайджанская Республика (Азербайджан)"], $this->statistics->regionsWhereNoDistributors()[0]);
    }
}
