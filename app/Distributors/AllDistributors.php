<?php

namespace App\Distributors;

class AllDistributors
{
     private const CONTACT_PATTERNS = [
         'emails' =>'/^[^@]*@[^@]*\.[^@]*$/',
         'domains'=> '%^((http?://)|(www\.))(([a-z0-9-].?)|([а-я0-9-].?))+(:[0-9]+)?(/.*)?$%i'
     ];

    public function getAllDistributorsPrepared($xml): array
    {
        $preparedCenters = [];
        $regname = [];
        foreach ($xml->region as $region) {
            if ((int)$region['centers'] === 0) {
                continue;//пропускаем, переходим к следующему региону
            }
            //получаем названия региона из аттрибутов региона
            $regname['regname'] = (string)$region->attributes()['regname'];
            //обходим все центры региона
            foreach ($region->center as $center) {
                //тут подготавливаем данные каждого центра, извлекаем emails, domains и т.д. в отдельном методе
                $preparedCenters[] = $this->getJustEmailsCity($center, $regname);
            }
        }
        return $preparedCenters;
    }

    private function getJustEmailsCity($center, $regname):array
    {
        //добавляем только нужные аттрибуты 'city', 'regname', 'domain', 'email'
            return [
                'city'=>(string)$center->attributes()['city'] ?? '',
                'regname' => $regname['regname'] ?? '',
                'name' => (string)$center->attributes()['name'] ?? '',
                'domains' => isset($center->attributes()['email']) ? $this->getDomainsEmails($center->attributes()['email'], 'domains') : [],
                'emails' => isset($center->attributes()['email']) ? $this->getDomainsEmails($center->attributes()['email'], 'emails') : [],
                'address' => (string)$center->attributes()['address'] ?? '',
                'phone' => (string)$center->attributes()['phone'] ?? '',
                'status' => (string)$center->attributes()['status'] ?? '',

            ];
    }

    private function getDomainsEmails($contact, $contactType): array
    {
        $contacts = [];
        $arrayOfEmailsDomains = explode(", ", $contact);
        //обходим полученный массив из имейла и домена
        foreach ($arrayOfEmailsDomains as $element) {
            //проверяем является ли элемент мейлом или сайтом, тогда добавляем в соответствующий массив
            if($this->isEmailDomain($element, $contactType)){
                $contacts[] = $element;
            }
        }
        return $contacts ?? [];
    }

    private function isEmailDomain($element,$contactType): bool
    {
        return preg_match(self::CONTACT_PATTERNS[$contactType], $element);
    }

}
