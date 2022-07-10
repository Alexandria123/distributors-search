<?php

namespace App\Distributors;

class ResultArray
{
    public static array $result;

    public function __construct($result){
        self::$result = $result;
    }

    public function arrayWithRegionsCenters(): array
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

                if ($keyEmail=='city'){
                    $emails[$keyEmail] = $email;
                }
                elseif($keyEmail=='email') {
                    $arrayOfEmailsDomains = explode(", ", $email);
                    foreach ($arrayOfEmailsDomains as $element) {
                            $emails= $this->getEmails($element, $emails);
                    }
                }
            }
            $resultEmails[]=$emails;
            $emails = null;
        }

        return $resultEmails;
    }

    private function getEmails($element, $emails):array
    {
        $isEmail = preg_match("/^[^@]*@[^@]*\.[^@]*$/", $element);
        $isDomain = preg_match('%^((https?://)|(www\.))([a-z0-9-].?)|([а-я0-9-].?)+(:[0-9]+)?(/.*)?$%i', $element);
        if ($isEmail) {
            $emails['emails'][] = $element;
        }
        elseif ($isDomain) {
            $emails['domains'][] = $element;
        }
        return $emails;
    }
}
