<?php

namespace Database\Factories;

use App\Distributors\Distributors;
use App\Models\Distributor;
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
            'id'=> '',
            'region_id'=>'',
            'name'=>'',
            'email'=>'',
            'domain'=>'',
            'address'=>'',
            'phone'=>'',
            'status'=>'',
            'city_id'=>'',
        ];
    }
}
