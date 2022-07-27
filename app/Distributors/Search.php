<?php

namespace App\Distributors;

class Search
{
    public function getEmailsbyCity($searchValue, $array): array
    {
        $resultSearch = [];
        foreach ($array as $elements){ //index
            foreach ($elements as $values) {
                if ($values == $searchValue) {
                    $resultSearch[] = $elements;
                }
            }
        }
        return $resultSearch;
    }
}
