<?php
class Pedido
{
    private $cliente;
    private $id;
    private $codigoPedido;
    private $codigoMesa;
    private $momentoEncargo;
    private $tiempoEncargo;
    private $tiempoEstimado;
    private $tiempoEntregado;
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
    public function GetTiempoEncargo()
    {
        return $this->tiempoEncargo;
    }
    public function GetTiempoEstimado()
    {
        return $this->tiempoEstimado;
    }
    public function GetTiempoEntregado()
    {
        return $this->tiempoEntregado;
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

    public function SetTiempoEncargo($tiempoEncargo)
    {
        $retorno = false;
        if (is_string($tiempoEncargo) && count_chars($tiempoEncargo) == 5) {
            $this->tiempoEncargo = $tiempoEncargo;
            $retorno = true;
        }

        return $retorno;
    }
    // public function SetTiempoEncargo($tiempoEntregado)
    // {
    //     $retorno = false;
    //     if (is_string($tiempoEntregado) && count_chars($tiempoEntregado) == 5) {
    //         $this->tiempoEntregado = $tiempoEntregado;
    //         $retorno = true;
    //     }

    //     return $retorno;
    // }
    public function SetTiempoEstimado($tiempoEstimado)
    {
        $retorno = false;
        if (is_string($tiempoEstimado) && count_chars($tiempoEstimado) == 5) {
            $this->tiempoEstimado = $tiempoEstimado;
            $retorno = true;
        }
        return $retorno;
    }




}