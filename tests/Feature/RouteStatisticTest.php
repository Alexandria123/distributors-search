<?php

namespace Tests\Feature;

use App\Models\Distributor;
use App\Models\Region;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;
use Illuminate\Database\Eloquent\Factories\Sequence;

class RouteStatisticTest extends TestCase
{
    use RefreshDatabase;

    public function testShouldAssertTheStatusAndJsonOfTheStatisticRouteSuccessfullyWhereMostOfDistributors()
    {
        Region::factory()->has(Distributor::factory()->count(2))
            ->state(new Sequence(
                ['name' => 'Архангельская обл.'],
                ['name' => 'Воронежская обл.'],
            ))->count(2)->create();
        Region::factory()->has(Distributor::factory()->count(3))
            ->state(new Sequence(
                ['name' => 'Амурская обл.'],
                ['name' => 'Белгородская обл.']
            ))->count(2)->create();
        $response = $this->get('/statistic/most');
        $response->assertStatus(200);
        $response->assertJson(fn (AssertableJson $json) =>
        $json->where('0.name', 'Амурская обл.')
            ->where('1.name', 'Белгородская обл.')
            ->where('2.name', 'Архангельская обл.')

            ->missing('Барнаул')
        );
    }

    public function testShouldAssertTheStatusAndJsonOfTheStatisticRouteUnsuccessfullyWhereMostOfDistributors()
    {
        $response = $this->get('/statistic/Most');
        $response->assertStatus(404);
    }

    public function testShouldAssertTheStatusOfTheStatisticRouteUriSuccessfullyWhereLeastOfDistributors()
    {
        Region::factory()->has(Distributor::factory()->count(2))
            ->state(new Sequence(
                ['name' => 'Архангельская обл.'],
                ['name' => 'Воронежская обл.'],
            ))->count(2)->create();
        Region::factory()->has(Distributor::factory()->count(3))
            ->state(new Sequence(
                ['name' => 'Амурская обл.'],
                ['name' => 'Белгородская обл.']
            ))->count(2)->create();
        $response = $this->get('/statistic/minimum');
        $response->assertJson(fn (AssertableJson $json) =>
        $json->where('0.name', 'Белгородская обл.')
            ->where('1.name', 'Архангельская обл.')
            ->where('2.name', 'Воронежская обл.')

            ->missing('Барнаул')
        );
        $response->assertStatus(200);
        $response->dd();
    }

    public function testShouldAssertTheStatusOfTheStatisticRouteUriUnsuccessfullyWhereLeastOfDistributors()
    {
        $response = $this->get('/statistic/min');
        $response->assertStatus(404);
    }

    public function testShouldAssertTheStatusOfTheStatisticRouteUriSuccessfullyWhereNoDistributors()
    {
        Region::factory()->create( ['name' => 'Азербайджанская Республика (Азербайджан)']);
        $response = $this->get('/statistic/none');
        $response->assertJson(fn (AssertableJson $json) =>
        $json->where('0.name', 'Азербайджанская Республика (Азербайджан)'));
        $response->assertStatus(200);
    }

    public function testShouldAssertTheStatusOfTheStatisticRouteUriUnsuccessfullyWhereNoDistributors()
    {
        $response = $this->get('/statistics/no');
        $response->assertStatus(404);
    }
}
