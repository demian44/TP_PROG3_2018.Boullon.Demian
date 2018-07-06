<?php

class Mesa extends Entity implements CodeGenerator
{
    private $code;
    private $status;

    /// Getters
    public function GetCode()
    {
        return $this->code;
    }

    public function GetStatus()
    {
        return $this->status;
    }

    // End Getters

    ///Setters
    public function SetCode($code)
    {
        $coso = count_chars($code);
        $retorno = false;
        if (is_string($code) && strlen($code) == 5) {
            $this->code = $code;
            $retorno = true;
        }

        return $retorno;
    }

    public function SetStatus($status)
    {
        $retorno = false;
        if (!is_null($status)) {
            $this->status = $status;
            $retorno = true;
        }

        return $retorno;
    }

    public function ToJson()
    {
        $mesaJson["id"] = $this->GetId();
        $mesaJson["code"] = $this->GetCode();
        $mesaJson["status"] = $this->GetStatus();

        return $mesaJson;
    }

    public static function generateCode()
    {
        $caracters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $count = strlen($caracters) - 1;
        //Genero un nuevo string con substrings aleatorios de 1 caracter de largo.
        return 'M' .
        substr($caracters, rand(0, $count), 1) . //2
        substr($caracters, rand(0, $count), 1) . //3
        substr($caracters, rand(0, $count), 1) . //4
        substr($caracters, rand(0, $count), 1); //5
    }
}
