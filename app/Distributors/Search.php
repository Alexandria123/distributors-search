<?php

namespace App\Distributors;

class Search
{
    public function getEmailsbyCity($req, $array, $i = 0)
    {
        $res = [];
        foreach ($array as $result=>$values){
            foreach ($values as $index=>$val){

                foreach ($val as $regnamecenters=>$elements){
                    if ($regnamecenters == 'centers'){
                        foreach ($elements as $indx=>$els){

                            if($array['result'][$index]['centers'][$indx]['city'] == $req) {
                                //echo $index . PHP_EOL;
                                $res[] =  $array['result'][$index]['centers'][$indx];
                            }
                        }
                    }
                }
            }
        }
        return $res;
    }

}
