<?php

class Mesa extends Entity implements CodeGenerator
{
    private $code;
    private $available;

    public function __construct($code)
    {
        $this->SetAvailable(true);

        return $this->SetCode($code);
    }

    /// Getters
    public function GetCode()
    {
        return $this->code;
    }

    public function GetAvailable()
    {
        return $this->available;
    }

    // End Getters

    ///Setters
    public function SetCode($code)
    {
        $retorno = false;
        if (is_string($code) && count_chars($code) == 5) {
            $this->code = $code;
            $retorno = true;
        }

        return $retorno;
    }

    public function SetAvailable($available)
    {
        $retorno = false;
        if (!is_null($available)) {
            $this->available = $available;
            $retorno = true;
        }

        return $retorno;
    }

    public static function generateCode()
    {
        $caracters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $count = strlen($caracters) - 1;
        //Genero un nuevo string con substrings aleatorios de 1 caracter de largo.
        return 'M'.
                    substr($caracters, rand(0, $count), 1). //1
                    substr($caracters, rand(0, $count), 1). //2
                    substr($caracters, rand(0, $count), 1). //3
                    substr($caracters, rand(0, $count), 1). //4
                    substr($caracters, rand(0, $count), 1); //5
    }
}
