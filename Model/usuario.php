<?php
/*
 * Los usuarios pueden ser socios o empleados
 */
class Usuario
{
    private $nombre;
    private $id;
    private $tipo;
     
    /// Getters
    public function GetCliente()
    {
        return $this->nombre;
    }
    public function GetId()
    {
        return $this->id;
    }
    public function GetTipo()
    {
        return $this->tipo;
    }
    public function GetCodigoMesa()
    {
        return $this->codigoMesa;
    }
    // End Getters

    ///Setters
    public function SetCliente($nombre)
    {
        $retorno = false;
        if (is_string($nombre) && $nombre != "") {
            $this->nombre = $nombre;
            $retorno = true;
        }
        return $retorno;
    }
    public function SetId($id)
    {
        $retorno = false;
        if (is_int($id) && $id >= 0) {
            $this->id = $id;
            $retorno = true;
        }
        return $retorno;
    }

    public function SetTipo($tipo)
    {
        $retorno = false;
        if (is_int($tipo) && ($tipo >= 0 || $tipo <= 5)) {
            $this->tipo = $tipo;
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

    public function MostrarDatos()
    {
        return $this->nombre . " - " . $this->sexo;
    }



}

class Tipo
{
    const SOCIO = 0;
    const BARTENDER = 1;
    const CERBECERO = 2;
    const COCINERO = 3;
    const MOZO = 4;
}


?>