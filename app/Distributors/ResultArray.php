<?php

namespace App\Distributors;

class ResultArray
{
    public static array $result;

    public function __construct($result){
        self::$result = $result;
    }

    public function ind(): array
    {
        return self::$result;
    }

}
