<?php
/*
 * Los usuarios pueden ser socios o empleados
 */
class User extends Entity
{
    private $name;
    private $user;
    private $pass;
    private $category;

    public function __construct($name, $user, $pass, $category)
    {
        $this->name = $name;
        $this->user = $user;
        $this->pass = $pass;
        $this->category = $category;
    }

    /// Getters
    public function GetName()
    {
        return $this->name;
    }

    public function GetUser()
    {
        return $this->user;
    }

    public function GetPass()
    {
        return $this->pass;
    }

    public function GetCategory()
    {
        return $this->category;
    }

    public function GetCodigoMesa()
    {
        return $this->codigoMesa;
    }

    // End Getters

    ///Setters
    public function SetName($name)
    {
        $retorno = false;
        if (is_string($name) && $name != '') {
            $this->name = $name;
            $retorno = true;
        }

        return $retorno;
    }

    public function SetPass($pass)
    {
        $retorno = false;
        if (is_string($pass) && $pass != '') {
            $this->pass = $pass;
            $retorno = true;
        }

        return $retorno;
    }

    public function SetTipo($category)
    {
        $retorno = false;
        if (is_int($category) && ($category >= 0 || $category <= 5)) {
            $this->category = $category;
            $retorno = true;
        }

        return $retorno;
    }

    public function SetCodigoMesa($codigoMesa)
    {
        $retorno = false;
        if (is_string($codigoMesa) && count_chars($codigoMesa) == 5) {
            $this->codigoMesa = $codigoMesa;
            $retorno = true;
        }

        return $retorno;
    }
}
