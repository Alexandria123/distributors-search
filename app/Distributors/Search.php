<?php

namespace App\Distributors;

class Search
{
    public function getEmailsbyCity($searchValue, $array)
    {
        $result = [];
        foreach ($array as $values){
            foreach ($values as $index=>$val){

                foreach ($val as $regnamecenters=>$elements){
                    if ($regnamecenters == 'centers'){
                        foreach ($elements as $indx=>$els){

                            if($array['result'][$index]['centers'][$indx]['city'] == $searchValue) {
                                //echo $index . PHP_EOL;
                                $result[] =  $array['result'][$index]['centers'][$indx];
                            }
                        }
                    }
                }
            }
        }
        return $result;
    }

}
