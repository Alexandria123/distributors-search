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
            $pattern = ['/\s+/', '/город/', '/г./', '/ё/'];
            $replacement = ['', '', '','/е/'];
            //Убираем пробелы, город меняем на г.
            $searchValue = mb_strtolower(preg_replace($pattern, $replacement, $searchValue));
            $lev = levenshtein($searchValue, $elements['city']);
            // если расстояние лев. меньше допустимого, добавляем значение
            if ($lev <= 7) {
                    $bestMatchCity[] = $elements;
            }
        }
        return $bestMatchCity;
    }
}
