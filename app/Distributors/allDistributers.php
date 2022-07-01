<?php

namespace App\Distributors;

class allDistributers
{
    public \SimpleXMLElement $xml_Kodeks;
    public static array $allXml;

    public function __construct()
    {
        $this->xml_Kodeks = simplexml_load_string(file_get_contents(public_path('systemXml/centers_kodeks.xml')));
    }

    public function getAllXml(): array
    {
        $xml = $this->xml_Kodeks;
        foreach ($xml->children() as $child) { //весь список необработанных данных
            self::$allXml[] = $child;
        }
        return self::$allXml;
    }

    public function array(): array
    {
        array_map(function ($childRegion) use (&$resultArray,&$region) {

            $attrValue = $childRegion->attributes();//аттрибуты региона
            if ($childRegion->attributes()->centers!=0) {
                foreach ($attrValue as $key=>$attrRegion) {
                        $key=='centers' ? $region[$key]= $this->getCentersArray($childRegion): $region[$key] = (string)$attrRegion;
                }
                $resultArray[] = $region;
            }
            return json_encode($resultArray);
        }, $this->getAllXml());
        return $resultArray;
    }

    private function getCentersArray($childRegion):array
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
