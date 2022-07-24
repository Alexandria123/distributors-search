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
        return array_map(function ($elements) use (&$sorted){
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
    }

    private function getJustEmailsCity($values):array
    {
        $resultEmails = [];
        $cities = [];
        $emailsDomains = [];
        foreach ($values as $centers) {
            foreach ($centers as $keyEmail => $email) {
                switch ($keyEmail) {
                    case ('city'):
                        $cities[$keyEmail] = $this->getCities($email);
                        break;
                    case ('email'):
                        $emailsDomains = $this->getDomainsEmails($keyEmail, $email);

                }
            }
            $citiesEmailsDomains = array_merge($cities, $emailsDomains);
            $resultEmails[]=$citiesEmailsDomains;
            $cities = null;
        }
        return $resultEmails;
    }

    private function getCities($email)
    {
            return $email;
    }

    private function getDomainsEmails($keyEmail, $email)
    {
        $emails = [];

            $arrayOfEmailsDomains = explode(", ", $email);
            foreach ($arrayOfEmailsDomains as $element) {
                $emails = $this->getEmails($element, $emails);
                $emails = $this->getDomains($element, $emails);
            }
            return $emails;
    }

    private function getEmails($element, $emails):array
    {
        $isEmail = preg_match("/^[^@]*@[^@]*\.[^@]*$/", $element);
        if ($isEmail) {
            $emails['emails'][] = $element;
        }
        return $emails;
    }
    private function getDomains($element, $emails):array
    {
        $isDomain = preg_match('%^((https?://)|(www\.))([a-z0-9-].?)|([а-я0-9-].?)+(:[0-9]+)?(/.*)?$%i', $element);
        if ($isDomain) {
            $emails['domains'][] = $element;
        }
        return $emails;
    }
}
