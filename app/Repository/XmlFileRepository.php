<?php

namespace App\Repository;

use Illuminate\Support\Facades\Storage;

class XmlFileRepository
{
    public function getXmlFileBySystemType($systemType): \SimpleXMLElement
    {
        return simplexml_load_string(Storage::disk('local')->get($this->xmlFile($systemType)));
    }

    private function xmlFile($systemType)
    {
        return config('systemType.'.$systemType);
    }
}
