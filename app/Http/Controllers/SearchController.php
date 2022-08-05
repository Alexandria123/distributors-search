<?php

namespace App\Http\Controllers;

use App\Distributors\AllDistributors;
use App\Distributors\Search;
use App\Http\Requests\SearchRequest;
use App\Repository\XmlFileRepository;

class SearchController extends Controller
{
    private XmlFileRepository $xmlFileRepository;
    private AllDistributors $allDistributors;

    public function __construct()
    {
        $this->xmlFileRepository = new XmlFileRepository();
        $this->allDistributors = new AllDistributors();
    }

    public function searchByCity(SearchRequest $request): array
    {
        $searchValue= $request->query('search');
        $search = new Search($this->getAllDistributors($request));
        return $search->getBestMatchingCity($searchValue);
    }

    private function getAllDistributors(SearchRequest $request): array
    {
        $systemType = $request->route('systemType');
        //получаем xml файл, передаем в метод
        $xml =  $this->xmlFileRepository->getXmlFileBySystemType($systemType);
        //возвращаем массив с подготовленными центрами и регионами
        return $this->allDistributors->getAllDistributorsPrepared($xml)->getJustEmailsCity();
    }
}
