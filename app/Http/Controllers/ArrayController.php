<?php

namespace App\Http\Controllers;

use App\Models\XmlData;
use Illuminate\Http\Request;
use phpDocumentor\Reflection\File;

class ArrayController extends Controller
{
    public array $resultArray = [];
    public array $elementsArray = [];
    public array $region = [];
    public array $allXml = [];
    public \SimpleXMLElement $xml;

    public function __construct(){
        $this->xml = simplexml_load_string(file_get_contents(public_path('systemXml/centers_kodeks.xml')));
    }

    public function getAllXml(): array
    {
        $xml = $this->xml;
        foreach ($xml->children() as $child) { //весь список
            $this->allXml[] = $child;
        }
        return $this->allXml;
    }

    //основной массив проходит по региону и центрам
    public function index(): array
    {
        $resultArray = $this->resultArray;
        $region = $this->region;

        array_map(function ($childRegion) use (&$resultArray,&$region, &$keyReg) {
            $attrRegion = $childRegion->attributes();//аттрибуты региона
            foreach ($childRegion->children() as $center) {//перебор центров
                foreach ($attrRegion as $keyRegion => $valueReg) { //для регионов
                    if ($keyRegion == "regname") {
                        $reg = $valueReg;
                        $keyReg = $keyRegion;
                        $region[$keyReg] = (string)$reg;
                    }
                }
                $attrCity = $center->attributes();//атрибуты центров
                $elementsArray = $this->attrLoop($attrCity);
                $elementsArray = array_merge($region, $elementsArray);

                $resultArray[] = $elementsArray;
            }
            return json_encode($resultArray);
        }, $this->getAllXml());
        return $resultArray;
    }

    public function insertDataToDB()
    {
       $resulArray =  $this->index();
       foreach ($resulArray as $elements) {
           $xmlData = new XmlData;
           $xmlData->regname = $elements['regname'];
           array_key_exists('city', $elements) ? $xmlData->city = $elements['city'] : $xmlData->city = null;
           array_key_exists('email', $elements) ? $xmlData->email = $elements['email'] : $xmlData->email = null;
           array_key_exists('domain', $elements) ? $xmlData->domain = $elements['domain'] : $xmlData->domain = null;
           $xmlData->save();
       }
    }

    //проходит по всем атрибутам
    public function attrLoop($attributes):array
    {
        $elementsArray = $this->elementsArray;
        $region = $this->region;
        foreach ($attributes as $key=>$attribute){
            switch ($key) {
                case ("email"):
                    $elementsArray = $this->getEmails($key, $attribute, $elementsArray);
                    break;
                case("city"):
                    $elementsArray[$key] = (string)$attribute;
            }
        }
        return $elementsArray;
    }

    private function getEmails($key, $attribute, $elementsArray): array
    {
//        $isEmail = str_contains((string)$attribute, "@");
//        $hasPoint = str_contains($attribute, ",");
//        $isDomain = ((str_contains($attribute, "http") || str_contains($attribute, "www")));
        //Нет имейлов и двумерный массив
        if (str_contains($attribute, ",") && !str_contains((string)$attribute, "@")) {
            $elementsArray = $this->splitDomain($attribute, $elementsArray);

        } elseif (str_contains($attribute, ",") && str_contains((string)$attribute, "@")){
            $elementsArray = $this->splitEmailDomain($attribute, $elementsArray);
        }
        elseif (str_contains((string)$attribute, "@")) {
            $elementsArray [$key] = (string)$attribute;
        } elseif (!str_contains((string)$attribute, "@")) {
            $elementsArray ['domain'] = (string)$attribute;
        }
        return $elementsArray;
    }

    private function split($attribute): array
    {
        return explode(", ", $attribute);
    }

    //если только домены (имейлов нету)
    private function splitDomain($attribute, $elementsArray): array
    {
        $split = $this->split($attribute);
        $arrayEmails['domain'] = $split;
        return array_merge($elementsArray, $arrayEmails) ;
    }

    //имейлы и домены, распределение в нужный массив
    private function splitEmailDomain($attribute, $elementsArray): array
    {
        $emailsAndDomains = [];
        $split = $this->split($attribute);
        if (count($split)>2){
            $emailsCount = $this->repeatsOfEmails($split);
            $domainsCount = $this->repeatsOfDomains($split);
            $emailsAndDomains = $this->sortArray($split, $emailsCount, $domainsCount);
        }
        elseif (count($split)==2) {
            foreach ($split as $item){
                if (str_contains($item, "@")) $emailsAndDomains['email'] = $item;
                else $emailsAndDomains['domain'] = $item;
            }
        }
        return array_merge($elementsArray, $emailsAndDomains);
    }

    //Когда длинна массива с доменом и имейлом больше 3, распределяет в два массива с двумерным и одномерными знач
    private function sortArray($split, $emailsCount, $domainsCount):array
    {
        if (!str_contains($split[0], "@") && $emailsCount>=2) {//первый элемент домен оставльные два имейла
           $arrayEm = array_reverse($split);
           $split= array('email'=>[$arrayEm[0],$arrayEm[1]], 'domain'=>$arrayEm[2]);
        }
        elseif ($domainsCount>=2){//есть два домена
            $split = array('domain'=>[$split[0],$split[1]], 'email'=>$split[2]);
        }
        return $split;
    }

    //считает сколько в массиве доменов (для определения в дв массив в sortArray())
    private function repeatsOfDomains($split): int
    {
        $domainsCount = 0;
        foreach ($split as $element){
            if (!(str_contains($element, "@"))){
                $domainsCount++;
            }
        }
        return $domainsCount;
    }

    //считает сколько в массиве имейлов  (для определения в дв массив в sortArray())
    private function repeatsOfEmails($split): int
    {
        $emailsCount = 0;
        foreach ($split as $element){
            if (str_contains( $element, "@")){
                $emailsCount++;
            }
        }
        return $emailsCount;
    }
}
