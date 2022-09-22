<?php

namespace App\Http\Controllers;

use App\Distributors\Statistics;

class RegionsController extends Controller
{
    private Statistics $statistics;

    public function __construct(){
        $this->statistics = new Statistics();
    }
    public function regionsWithCities(): array
    {
        return $this->statistics->allRegionsWithCities();
    }

    public function regionsWhereMostDistributors(): array
    {
       return $this->statistics->getRegionsWhereMostDistributors();
    }

    public function regionsWhereLeastDistributors(): array
    {
        return $this->statistics->getRegionsWhereLeastDistributors();
    }

    public function regionsWhereNoDistributors(): array
    {
        return $this->statistics->regionsWhereNoDistributors();
    }
}
