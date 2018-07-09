<?php

class USER_CATEGORY
{
    const SOCIO = 0;
    const BARTENDER = 1;
    const CERBECERO = 2;
    const COCINERO = 3;
    const COCINERO_CANDY = 4;
    const MOZO = 5;
    
    public static function String(int $category): string
    {
        $return = "";
        switch ($category) {
            case Self::SOCIO:
                $return = "socio";
                break;
            case Self::BARTENDER:
                $return = "bartender";
                break;
            case Self::CERBECERO:
                $return = "cerbecero";
                break;
            case Self::COCINERO:
                $return = "cocinero";
                break;
            case Self::COCINERO_CANDY:
                $return = "cocinero pastelero";
                break;
            case Self::MOZO:
                $return = "mozo";
                break;
        }
        return $return;
    }
}
