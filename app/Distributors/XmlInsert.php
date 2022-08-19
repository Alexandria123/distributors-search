<?php

namespace App\Distributors;

use App\Models\City;
use App\Models\Distributor;
use App\Models\Region;
use App\Repository\XmlFileRepository;
use Illuminate\Support\Facades\DB;

class XmlInsert
{
    private const CONTACT_PATTERNS = [
        'email' =>'/^[^@]*@[^@]*\.[^@]*$/',
        'domain'=> '%^((http?://)|(www\.))(([a-z0-9-].?)|([а-я0-9-].?))+(:[0-9]+)?(/.*)?$%i'
    ];
    private XmlFileRepository $xmlFile;

    public function __construct()
    {
        $this->xmlFile = new XmlFileRepository();
    }

    public function insertToDBXmlData($systemType): void
    {
        foreach ($this->xmlFile->getXmlFileBySystemType($systemType)->region as $region) {
            $regions = new Region();
            $regions->name = $region->attributes()['regname'];
            $regions->centers = (int)$region->attributes()['centers'];
            $regions->save();
            foreach ($region->center as $center) {
                //Добавление городов
                $city = new City();
                //Проверяем отсутсвует ли город в базе
                $cityDoesntExist = DB::table('cities')->where('name', $center->attributes()['city'])->doesntExist();
                //Получаем регион
                $region_id = DB::table('regions')->where('name', $region->attributes()['regname'])->first();
                //Добавляем город, если такого города нет в бд, добавляем id_region в таблицу городов
                if(isset($center->attributes()['city']) && $cityDoesntExist)
                {
                    $city->region_id = $region_id->id;
                    $city->name = (string)$center->attributes()['city'];
                    $city->save();
                }
                //Добавление дистрибюторов
                $distributor = new Distributor();
                $distributor->id = (int)$center->attributes()['id'];
                //Получаем id по названию региона, добавляем
                $distributor->region_id = $region_id->id;
                //Получаем нужный город из таблицы городов
                //получаем id по значению города, добавляем в таблицу дистрибьюторов
                if(isset($center->attributes()['city'])) {
                    $city_id = DB::table('cities')->where('name', $center->attributes()['city'])->first();
                    $distributor->city_id = $city_id->id;
                }
                $distributor->name = (string)$center->attributes()['name'];
                $distributor->email = json_encode(isset($center->attributes()['email']) ? $this->getDomainsEmails($center->attributes()['email'], 'email') : []);
                $distributor->domain = json_encode(isset($center->attributes()['email']) ? $this->getDomainsEmails($center->attributes()['email'], 'domain') : []);
                $distributor->address = (string)$center->attributes()['address'];
                $distributor->phone = (string)$center->attributes()['phone'];
                $distributor->status = (string)$center->attributes()['status'];
                $distributor->save();
            }
        }
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
