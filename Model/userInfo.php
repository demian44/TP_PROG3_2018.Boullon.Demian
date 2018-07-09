<?php
/*
 * Los usuarios pueden ser socios o empleados
 */
class UserInfo extends User
{
    private $cantAperaciones;
    private $operation;
    private $login;

    /// Getters
    public function GetCantAperaciones()
    {
        return $this->cantAperaciones;
    }

    public function GetOperation()
    {
        return $this->operation;
    }

    public function GetLogin()
    {
        return $this->login;
    }

    public function GetCodigoMesa()
    {
        return $this->codigoMesa;
    }

    // End Getters

    ///Setters
    public function SetCantAperaciones(int $cantAperaciones)
    {
            $this->cantAperaciones = $cantAperaciones;
    }

    public function SetLogin($login)
    {
        $this->login = $login;
    }

    public function SetTipo($estado)
    {
        $retorno = false;
        if (is_int($estado) && ($estado >= 0 || $estado <= 5)) {
            $this->estado = $estado;
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

    public function SetOperation($codigoMesa)
    {
        $retorno = false;
        if (is_string($operation)) {
            $this->operation = $operation;
            $retorno = true;
        }

        return $retorno;
    }

    public function GetGeneralInfo()
    {
        $userInfo["usuario"] = $this->GetUser();
        $userInfo["cantidadOperaciones"] = $this->GetCantAperaciones();
        $userInfo["category"] = $this->GetCategory();
        $userInfo["estado"] = $this->GetActive();
        $userInfo["ultimoLogin"] = $this->GetLogin();

        return $userInfo;
    }

}
