<?php

namespace App\Distributors;

class Search
{
    private array $array;

    public function __construct($array){
        $this->array = $array;
    }

    public function getBestMatchingCity($searchValue): array
    {
        $bestMatchCity = [];
        foreach ($this->array as $elements) {
            //Убираем пробелы,  меняем город, г.,ё у клиентской строки
            $searchValue = $this->valueReplace($searchValue);
            //Получаем расстояние
            $lev = levenshtein($searchValue, $this->valueReplace($elements['city']));
            // если расстояние лев. меньше допустимого, добавляем значение
            if ($lev <= 4) {
                    $bestMatchCity[] = $elements;
            }
        }
        return $bestMatchCity;
    }

    private function valueReplace($value): array|string|null
    {
        $pattern = ['/\s+/', '/город/', '/г./', '/ё/'];
        $replacement = ['', '', '','/е/'];
        return mb_strtolower(preg_replace($pattern, $replacement, $value));
    }
}
