<?php

namespace App\Distributors;

use App\Models\City;
use App\Models\Distributor;
use App\Models\Region;
use App\Repository\XmlFileRepository;

class XmlInsert
{
    private const CONTACT_PATTERNS = [
        'emails' =>'/^[^@]*@[^@]*\.[^@]*$/',
        'domains'=>  '%^((http?://)|(www\.))(([a-z0-9-].?)|([а-я0-9-].?))+(:[0-9]+)?(/.*)?$%i'
    ];
    private XmlFileRepository $xmlFile;

    public function __construct()
    {
        $this->xmlFile = new XmlFileRepository();
    }

    public function insertToDBXmlData($systemType): void
    {
        foreach ($this->xmlFile->getXmlFileBySystemType($systemType)->region as $xmlRegion) {
            //Добавление регионов
            $region = Region::create(['name' => $xmlRegion->attributes()['regname']]);

            $addedCitiesID = [];
            $city = NULL;
            foreach ($xmlRegion->center as $center) {
                //Добавление городов
                //проверяем отсутсвует ли город в базе
                //Добавляем город, если такого города нет в бд. Добавляем region_id в таблицу городов
                if(isset($center->attributes()['city']) && !array_key_exists((string)$center->attributes()['city'], $addedCitiesID))
                {
                    $city = City::create([
                        'name' => (string)$center->attributes()['city'],
                        'region_id' => $region->id
                    ]);
                    //Массив для получения по ключу(названию города) Id города для сохранения в дистрибютора(city_id)
                    $addedCitiesID[(string)$center->attributes()['city']] = $city->id;
                }
                //Добавление дистрибьюторов
                $distributor = Distributor::create(['id' => (int)$center->attributes()['id'],
                    'region_id' => $region->id,
                    'city_id' => $addedCitiesID[(string)$center->attributes()['city']] ?? NULL,
                    'name' => (string)$center->attributes()['name'],
                    'emails' => json_encode(isset($center->attributes()['email']) ? $this->getContacts($center->attributes()['email'], 'emails') : []),
                    'domains' => json_encode(isset($center->attributes()['email']) ? $this->getContacts($center->attributes()['email'], 'domains') : []),
                    'address' => (string)$center->attributes()['address'],
                    'phone' => (string)$center->attributes()['phone'],
                    'status' => (string)$center->attributes()['status']
                ]);
            }
        }
    }

    private function getContacts($contact, $contactType): array
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
