<?php

namespace Database\Factories;

use App\Distributors\XmlInsert;
use App\Models\City;
use App\Models\Distributor;
use App\Models\Region;
use Illuminate\Database\Eloquent\Factories\Factory;


/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Distributor>
 */
class DistributorFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'id'=> $this->faker->unique()->randomNumber(2, false),
          //  'region_id'=>Region::factory(),
            'name'=>$this->faker->company(),
            'email'=> json_encode($this->faker->email()),
            'domain'=> json_encode($this->faker->domainName()),
            'address'=> $this->faker->address(),
            'phone'=> $this->faker->PhoneNumber(),
            'status'=> 'Дистрибьютор',
           // 'city_id'=> City::factory(),
        ];
    }
}
