<?php

namespace App\Distributors;

class ResultArray
{
    public static array $result;
    public static array $sortedArray;

    public function __construct($result){
        self::$result = $result;
    }

    public function arrayWithCentersRegions(): array
    {
        $sorted = [];
        $sortedArray['result'] = array_map(function ($elements) use (&$sorted){
            foreach ($elements as $key=>$values){
                if ($key == 'regname') {
                    $sorted[$key] = $values;
                }
                elseif ($key == 'centers') {
                        $sorted[$key] = $this->getJustEmails($values);
                }
            }
            return $sorted;
        },self::$result);

        return $sortedArray;
    }

    private function getJustEmails($values):array
    {
        $resultEmails = [];
        $emails = [];
        foreach ($values as $centers) {
            foreach ($centers as $keyEmail => $email) {
                switch ($keyEmail) {
                    case('email'):
                        $emails[$keyEmail] = $email;
                        break;
                    case ('city'):
                        $emails[$keyEmail] = $email;
                }
            }
            $resultEmails[] = $emails;
        }
        return $resultEmails;
    }
}
