<?php

namespace App\Distributors;

class AllDistributors
{
    public static array $allXml;
    public \SimpleXMLElement $xmlValue;

    public function getAllXml($xmlValue): array
    {
        $this->xmlValue = $xmlValue;
        foreach ($xmlValue->children() as $child) { //Весь список необработанных данных
            self::$allXml[] = $child;
        }
        return self::$allXml;
    }

    public function array($xmlValue): ResultArray
    {
        array_map(function ($childRegion) use (&$resultArray,&$region) {
            $attrValue = $childRegion->attributes();//Аттрибуты региона
            if ($childRegion->attributes()->centers!=0) {
                foreach ($attrValue as $key=>$attrRegion) {
                        $key=='centers' ? $region[$key]= $this->getCentersAttributesArray($childRegion): $region[$key] = (string)$attrRegion;
                }
                $resultArray[] = $region;
            }
            return json_encode($resultArray);
        }, $this->getAllXml($xmlValue));
        return new ResultArray($resultArray); //Получаем обработанные данные со всеми аттрибутами и всем
    }

    private function getCentersAttributesArray($childRegion):array
    {
        $centers = [];
        $centersAll = [];
        foreach ($childRegion->children() as $children) {
            foreach ($children->attributes() as $keyC => $center) {
                $centers[$keyC] = (string)$center;
            }
            $centersAll[] = $centers;
        }
        return $centersAll;
    }
}
