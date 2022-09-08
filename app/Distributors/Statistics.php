<?php

namespace App\Distributors;

use App\Models\Distributor;
use App\Models\Region;

class Statistics
{
    private $distributorsCountRegions;

    public function __construct()
    {
        $this->distributorsCountRegions =  Region::join('distributors', 'regions.id', 'distributors.region_id')
            ->select('regions.name')
            ->selectRaw('count(distributors.region_id) as count')
            ->groupBy('distributors.region_id')
            ->orderBy('count', 'DESC')
            ->get()->toArray();
    }

    public function allRegionsWithCities(): array
    {
        $regionCity = [];
        $distributors = Distributor::with('region')->with('city')->get();
        foreach ($distributors as $distributor){
            $regionCity[] = $distributor->region->name;
            $regionCity[] = $distributor->city->name??[];
        }
        return $regionCity;
    }

    public function getRegionsWhereMostDistributors(): array
    {
        return  array_slice($this->distributorsCountRegions, 0,3);
    }

    public function getRegionsWhereLeastDistributors(): array
    {

        return array_slice($this->distributorsCountRegions, -3, 3);
    }

    public function regionsWhereNoDistributors()
    {
        return Region::leftJoin('distributors', 'regions.id', 'distributors.region_id')
            ->select('regions.name')
            ->where('distributors.region_id', '=', null)
            ->get();
    }
}
