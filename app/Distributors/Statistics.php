<?php

namespace App\Distributors;

use App\Models\Distributor;
use Illuminate\Support\Facades\DB;

class Statistics
{
    private array $distributorsCountRegions;

    public function __construct(){
        //Получаем id регионов без повторений
        $regions = DB::table('distributors')->select('region_id')->distinct()->pluck('region_id');
        //Обходим id регионов ($regions) и делаем запрос к таблице дистрибьютора
        // там где id совпадает подсчитываем, получаем количество совпавших id региона.
        //$regionName - получаем дистрибьютора с регионом (через связь).
        //вторым элементом массива добавляем название региона из $regionName
        foreach ($regions as $regionId){
            $regionName = Distributor::with('region')->where('region_id', $regionId)->first();
            $this->distributorsCountRegions[] =  ['count' => DB::table('distributors')->where('region_id', $regionId)->count(),
                'region'=>$regionName->region->name];
        }
    }
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
        rsort($this->distributorsCountRegions); //Сортируем по убыванию
        return  [$this->distributorsCountRegions[0]['region'], $this->distributorsCountRegions[1]['region'], $this->distributorsCountRegions[2]['region']];
    }

    public function getregionsWhereLeastDistributors(): array
    {
        sort($this->distributorsCountRegions);
        return [$this->distributorsCountRegions[0]['region'], $this->distributorsCountRegions[1]['region'], $this->distributorsCountRegions[2]['region']];
    }

    public function regionsWhereNoDistributors(): array
    {
        $regionsWithoutCenters = [];
        //Получаем id и название из таблицы регионов
        $regions = DB::table('regions')->pluck('name', 'id');
        //Обходим все id регионов
        foreach ($regions as $id=>$name){
            //Проверяем есть ли id региона в таблице Дитсрибьютора, если нет, то у региона нету центра, получаем этот id из таблицы, добаавляем в массив
            if(DB::table('distributors')->where('region_id', $id)->doesntExist()){
                $regionsWithoutCenters[] = DB::table('regions')->where('id', $id)->first('name');
            }
        }
        return $regionsWithoutCenters;
    }
}
