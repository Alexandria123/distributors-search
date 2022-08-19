<?php

namespace App\Http\Controllers;

use App\Distributors\Statistics;
use Illuminate\Support\Collection;

class RegionsController extends Controller
{
    private Statistics $statistics;

    public function __construct(){
        $this->statistics = new Statistics();
    }
    public function regionsWithCities(): array
    {
        return $this->statistics->regionsWithCities();
    }

    public function regionsWhereMostDistributors(): array
    {
       return $this->statistics->getregionsWhereMostDistributors();
    }

    public function regionsWhereLeastDistributors(): array
    {
        return $this->statistics->getregionsWhereLeastDistributors();
    }

    public function regionsWhereNoDistributors(): Collection
    {
        return $this->statistics->regionsWhereNoDistributors();
    }
}
