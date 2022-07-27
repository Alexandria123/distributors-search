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
        $regions = [];
        $nestedArrayDistributors = array_map(function ($elements) use (&$sorted, &$regions){
            foreach ($elements as $key=>$values){
                switch ($key) {
                    case('regname'):
                        $regions[$key] = $values;
                        break;
                    case('centers'):
                        $sorted = $this->getJustEmailsCity($values, $regions);
                }
            }
            return $sorted;
        },self::$result);

        return $this->getFlattenedDistributors($nestedArrayDistributors);
    }

    private function getJustEmailsCity($values, $regions):array
    {
        $regionsCitiesEmailsDomains = [];
        $cities = [];
        $emailsDomains = [];

            foreach ($values as $centers) {
                foreach ($centers as $keyEmail => $email) {
                    switch ($keyEmail) {
                        case ('city'):
                            $cities[$keyEmail] = $email;
                            break;
                        case ('email'):
                            $emailsDomains = $this->getDomainsEmails($email);
                    }
                }
                $regionsCitiesEmailsDomains[] = array_merge($regions, $cities, $emailsDomains);
                $cities = null;
            }
        return $regionsCitiesEmailsDomains;
    }

    private function getDomainsEmails($email)
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

    private function getFlattenedDistributors(array $nestedArrayDistributors): array
    {
        $distributors = [];
        foreach ($nestedArrayDistributors as $elementsByRegions){
            foreach ($elementsByRegions as $elements){
                $distributors[] = $elements;
            }
        }
        return $distributors;
    }
}
