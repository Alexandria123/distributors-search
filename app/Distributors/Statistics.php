<?php

namespace App\Distributors;

use App\Models\Distributor;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class Statistics
{
    public function regionsWithCities(): array
    {
        $regionCity = [];
        $distributors = Distributor::with('region')->with('city')->get();
        foreach ($distributors as $distributor){
            $regionCity[] = $distributor->region->name;
            $regionCity[] = $distributor->city->name??[];
        }
        return $regionCity;
    }

    public function getregionsWhereMostDistributors(): array
    {
        $mostDistributors =  DB::table('regions')->orderByDesc('centers')->get();
        return [$mostDistributors[0], $mostDistributors[1], $mostDistributors[2]];
    }

    public function getregionsWhereLeastDistributors(): array
    {
        $minDistributors = DB::table('regions')->orderBy('centers')->where('centers', '>', 0)->get();
        return [$minDistributors[0], $minDistributors[1], $minDistributors[2]];
    }

    public function regionsWhereNoDistributors(): Collection
    {
        return DB::table('regions')->where('centers', '=', 0)->get();
    }
}
