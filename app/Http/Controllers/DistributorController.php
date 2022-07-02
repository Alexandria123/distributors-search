<?php

namespace App\Http\Controllers;

use App\Distributors\allDistributers;
use App\Http\Requests\StoreSystemRequest;
use Illuminate\Http\Request;

class DistributorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
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
        return app(allDistributers::class)->array($xml[$xmlKey]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($xmlKey)
    {

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $xmlKey)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($xmlKey)
    {
        //
    }
}
