<?php

namespace App\Http\Controllers;

use App\Distributors\AllDistributors;
use App\Distributors\Search;
use Illuminate\Http\Request;

class ArrayController extends Controller
{

    public function index($xmlKey)
    {
        $xml = [];
        if(!in_array($xmlKey, ['kodeks','techexpert'])){
            abort(404);
        }
        switch ($xmlKey) {
            case('kodeks'):
                $xml[$xmlKey] = simplexml_load_string(file_get_contents(public_path('systemXml/centers_kodeks.xml')));
                break;
            case('techexpert'):
                $xml[$xmlKey] = simplexml_load_string(file_get_contents(public_path('systemXml/centers_techexpert.xml')));
        }
        // $validated = $request->validate($xmlKey);

        $app = app(AllDistributors::class)->array($xml[$xmlKey]);
        return $app->arrayWithRegionsCenters();
    }

    public function store(Request $request, $xmlKey)
    {
        $searchValue= $request->query('search');
        $array = $this->index($xmlKey);
        return app(Search::class)->getEmailsbyCity($searchValue, $array);


    }
}
