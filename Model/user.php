<?php
/*
 * Los usuarios pueden ser socios o empleados
 */
class User extends Entity
{
    private $name;
    private $user;
    private $pass;
    private $perfil;

    public function __construct($name, $user, $pass, $perfil)
    {
        $this->name = $name;
        $this->perfil = $perfil;
        $this->user = $user;
        $this->pass = $pass;
    }

    /// Getters
    public function GetName()
    {
        return $this->name;
    }
    public function GetPerfil()
    {
        return $this->perfil;
    }

    public function GetUser()
    {
        return $this->user;
    }

    public function GetPass()
    {
        return $this->pass;
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
    public function NamePerfilFromDB($namePerfil)
    {
        $retorno = false;
        $arrayString = explode("-", $namePerfil);
        $this->name = $arrayString[0];
        $this->perfil = $arrayString[1];
        return $retorno;
    }
    public function SetPerfil($perfil)
    {
        $retorno = false;
        if (is_string($perfil) && $perfil != '') {
            $this->perfil = $perfil;
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

    public function SetUser($user)
    {
        $retorno = false;
        if (is_string($user) && $user != '') {
            $this->user = $user;
            $retorno = true;
        }

        return $retorno;
    }

}
