<?php

namespace App\Repository;

use Illuminate\Support\Facades\Storage;

class XmlFileRepository
{
    public function getXmlFileBySystemType($systemType): \SimpleXMLElement
    {
        $xml[$systemType] = match($systemType) {
            'kodeks' => simplexml_load_string(Storage::disk('local')->get('systemXml/centers_kodeks.xml')),
            'techexpert' => simplexml_load_string(Storage::disk('local')->get('systemXml/centers_techexpert.xml')),
        };
        return $xml[$systemType];
    }
}
