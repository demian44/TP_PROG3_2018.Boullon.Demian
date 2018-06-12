<?php
class Mesa
{    
    private $id;
    private $codigoMesa;
    private $estado;
    /// Getters
    public function GetCliente()
    {
        return $this->cliente;
    }
    public function GetId()
    {
        return $this->id;
    }
    public function GetCodigoPedido()
    {
        return $this->codigoPedido;
    }
    public function GetCodigoMesa()
    {
        return $this->codigoMesa;
    }
    // End Getters

    ///Setters
    public function SetCliente($cliente)
    {
        $retorno = false;
        if (is_string($cliente) && $cliente != "") {
            $this->cliente = $cliente;
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
        if (is_string($codigoPedido) && count_chars($codigoMesa) == 5) {
            $this->codigoPedido = $codigoPedido;
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