<?php

namespace App\Services;


class SecuriteUrlService
{


//    private $dans = $_SERVER["REQUEST_URI"];

    public function securiserUrl(?string $participant, ?string $modifier, ?string $uri){

        $separation1 = substr($uri, 0, -strposi($modifier));
        dd($separation1);
    }

//    public function recupUri(){ return $_SERVER["REQUEST_URI"]; }


//    public function after (?string $debut, ?string $dans)
//    {
//        if (!is_bool(strposi($dans, $debut)))
//            return substr($dans, strposi($dans,$debut)+strlen($debut));
//    }
//
//
//    public function before (?string $fin, ?string $dans)
//    {
//        return substr($dans, 0, strposi($dans, $fin));
//    }


//    public function between (?string $debut, ?string $fin, ?string $inThat)
//    {
//        return before ($fin, after($debut, $inThat));
//    }


}