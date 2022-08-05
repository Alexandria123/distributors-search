<?php

namespace App\Distributors;

class ResultArray
{
    static private array $isDomainOrEmail = ['email' =>'/^[^@]*@[^@]*\.[^@]*$/',
                                            'domain'=> '%^((http?://)|(www\.))(([a-z0-9-].?)|([а-я0-9-].?))+(:[0-9]+)?(/.*)?$%i'
                                            ];
    public function __construct(private array $preparedCenters){
       // self::$preparedCenters = $preparedCenters;
    }

    public function getJustEmailsCity():array
    {
        $preparedCenters = $this->preparedCenters;
        $regionsCitiesEmailsDomains = [];

        //обохдим и добавляем только нужные аттрибуты 'city', 'regname', 'email'
        foreach ($preparedCenters as $key=>$value) {
            $regionsCitiesEmailsDomains[] = [
                'city'=>$value['city'] ?? [],
                'regname' => $value['regname'] ?? [],
                'domain' => isset($value['email']) ? $this->getDomainsEmails($value['email'], 'domain') : [],
                'email' => isset($value['email']) ? $this->getDomainsEmails($value['email'], 'email') : []
            ];
        }
        return $regionsCitiesEmailsDomains;
    }

    private function getDomainsEmails($email, $contactType): array
    {
        $emails = [];
        $arrayOfEmailsDomains = explode(", ", $email);
        //обходим полученный массив из имейла и домена
        foreach ($arrayOfEmailsDomains as $element) {
            //проверяем является ли элемент мейлом или сайтом, тогда добавляем в соответствующий массив
            if($this->isEmailDomain($element, $contactType)){
                if($contactType=='email')$emails['email'][]= $element;
                else $emails['domain'][]=$element;
            }
        }
        return $emails[$contactType] ?? [];
    }

    private function isEmailDomain($element,$contactType): bool
    {
        return preg_match(self::$isDomainOrEmail[$contactType], $element);
    }
}
