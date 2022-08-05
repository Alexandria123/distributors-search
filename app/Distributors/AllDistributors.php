<?php

namespace App\Distributors;

class AllDistributors
{
    public function getAllDistributorsPrepared($xml): ResultArray
    {
        $preparedCenters = [];
        $regname = [];
        foreach ($xml->region as $region) {
            if ((int)$region['centers'] === 0) {
                continue;//пропускаем, переходим к следующему региону
            }
            //получаем названия региона из аттрибутов региона
            $regname['regname'] = (string)$region->attributes()['regname'];
            //обходим все центры региона
            foreach ($region->center as $center) {
                //тут подготавливаем данные каждого центра, извлекаем emails, domains и т.д. в отдельном методе
                $preparedCenters[] = $this->getCentersAttributesArray($center, $regname);
            }
        }
        return new ResultArray($preparedCenters);
    }

    private function getCentersAttributesArray($center, $regname):array
    {
        $centers = [];
        //обходим аттрибуты цетнра, добавляем в массив и объединяем с регионами
        foreach ($center->attributes() as $keyC => $center) {
            $centers[$keyC] = (string)$center;
        }
        return array_merge($centers, $regname);
    }
}
