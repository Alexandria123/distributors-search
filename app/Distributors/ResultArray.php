<?php

namespace App\Distributors;

class ResultArray
{
    public static array $result;

    public function __construct($result){
        self::$result = $result;
    }

    public function arrayWithCentersRegions(): array
    {
        $sorted = [];
        $sortedArray['result'] = array_map(function ($elements) use (&$sorted){
            foreach ($elements as $key=>$values){
                switch ($key) {
                    case('regname'):
                        $sorted[$key] = $values;
                        break;
                    case('centers'):
                        $sorted[$key] = $this->getJustEmailsCity($values);
                }
            }
            return $sorted;
        },self::$result);

        return $sortedArray;
    }

    private function getJustEmailsCity($values):array
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
