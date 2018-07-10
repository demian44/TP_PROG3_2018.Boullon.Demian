<?php

class ITEM_TYPE
{
    const FOOD = 0;
    const DESSERT = 1;
    const DRINK = 2;
    const BEER = 3;
    const WINE = 4;

    public static function String(int $tipo){
        switch ($tipo) {
            case Self::FOOD:
                return "FOOD";
                break;
            case Self::DESSERT:
                return "DESSERT";
                break;
            case Self::DRINK:
                return "DRINK";
                break;
            case Self::BEER:
                return "BEER";
                break;
            case Self::WINE:
                return "WINE";
                break;
            default:
                return "UNDEFINDES";
                break;
            
        }

    }
}
