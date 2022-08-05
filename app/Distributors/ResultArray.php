<?php

namespace App\Distributors;

class ResultArray
{
    public function __construct(private array $preparedCenters){
       // self::$preparedCenters = $preparedCenters;
    }

    public function getJustEmailsCity(): array
    {
        $preparedCenters = $this->preparedCenters;
        $regionsCitiesEmailsDomains = [];
        $cities = [];
        $emailsDomains = [];
        //обохдим и добавляем только нужные аттрибуты 'city', 'regname', 'email'
        foreach ($preparedCenters as $value) {
            foreach ($value as $keyEmail => $email) {
                switch ($keyEmail) {
                    case ('city'):
                    case ('regname'):
                        $cities[$keyEmail] = $email;
                        break;
                    case('email'):
                        $emailsDomains = $this->getDomainsEmails($email);
                }
            }
            $regionsCitiesEmailsDomains[] = array_merge($cities, $emailsDomains);
        }
        return $regionsCitiesEmailsDomains;
    }

    private function getDomainsEmails($email): array
    {
        $emails = [];
        $arrayOfEmailsDomains = explode(", ", $email);
        //обходим полученный массив из имейла и домена
        foreach ($arrayOfEmailsDomains as $element) {
            //проверяем является ли элемент мейлом или сайтом, тогда добавляем в соответствующий массив
            if($this->isEmail($element)) $emails['email'][]= $element;
            if($this->isDomain($element)) $emails['domain'][]= $element;
        }
        return $emails;
    }

    private function isEmail($element): bool
    {
        $isEmail = preg_match("/^[^@]*@[^@]*\.[^@]*$/", $element);
        if ($isEmail) {
            return true;
        }
        else return false;
    }

    private function isDomain($element): bool
    {
        $isDomain = preg_match('%^((http?://)|(www\.))(([a-z0-9-].?)|([а-я0-9-].?))+(:[0-9]+)?(/.*)?$%i', $element);
        if ($isDomain) {
            return true;
        }
        else return false;
    }
}
