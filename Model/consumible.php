<?php
class Consumible
{
    private $id;
    private $codigoPedido;
    private $nombre;
    private $descripcion;
    private $cant;
    private $disponible;
    
    /// Getters
    public function GetNombre()
    {
        return $this->nombre;
    }
    public function GetDescripcion()
    {
        return $this->descripcion;
    }
    public function GetId()
    {
        return $this->id;
    }
    public function GetCodigoPedido()
    {
        return $this->codigoPedido;
    }

    public function GetCant()
    {
        return $this->cant;
    }
    public function GetDisponible()
    {
        return $this->disponible;
    }
    // End Getters
    
    ///Setters
    public function SetNombre($nombre)
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
    public function SetCodigoPedido($codigoPedido)
    {
        $retorno = false;
        if (is_int($codigoPedido) && $codigoPedido >= 0) {
            $this->codigoPedido = $codigoPedido;
            $retorno = true;
        }
        return $retorno;
    }
    public function SetCodigoDescripcion($descripcion)
    {
        $retorno = false;
        if (is_string($descripcion) && count_chars($descripcion) == 5) {
            $this->descripcion = $descripcion;
            $retorno = true;
        }

        return $retorno;
    }
    public function SetCant($cant)
    {
        $retorno = false;
        if (is_string($cant) && count_chars($cant) == 5) {
            $this->cant = $cant;
            $retorno = true;
        }

        return $retorno;
    }

    // End Setters
    

}