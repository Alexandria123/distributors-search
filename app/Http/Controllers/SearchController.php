<?php

namespace App\Http\Controllers;

use App\Distributors\AllDistributors;
use App\Distributors\Search;
use App\Http\Requests\SearchRequest;
use App\Repository\XmlFileRepository;

class SearchController extends Controller
{

    public function allDistributorsSortedArray($systemType)
    {
        $xml = app(XmlFileRepository::class)->getXmlFileBySystemType($systemType);
        $app = app(AllDistributors::class)->array($xml);
        return $app->arrayWithRegionsCenters();
    }

    public function searchByCity(SearchRequest $request, $systemType)
    {
        $searchValue= $request->query('search');
        $array = $this->allDistributorsSortedArray($systemType);
        return app(Search::class)->getEmailsbyCity($searchValue, $array);
    }
}
