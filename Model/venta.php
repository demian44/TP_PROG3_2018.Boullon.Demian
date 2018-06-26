<?php
class Venta extends Foto
{
    private $idMedia;
    private $nombreCliente;
    private $fecha;
    private $importe;

    public function __construct($idMedia, $nombreCliente, $fecha, $importe)
    {
        $this->idMedia = $idMedia;
        $this->nombreCliente = $nombreCliente;
        $this->fecha = $fecha;
        $this->importe = $importe;
    }

    /// Getters
    public function GetIdMedia()
    {
        return $this->idMedia;
    }

    public function GetImporte()
    {
        return $this->importe;
    }

    public function GetNombreCliente()
    {
        return $this->nombreCliente;
    }

    public function GetFecha()
    {
        return $this->fecha;
    }

    // End Getters

    ///Setters
    public function SetIdMedia($idMedia)
    {
        $retorno = false;
        if (is_numeric($idMedia) && $idMedia > 0) {
            $this->idMedia = $idMedia;
            $retorno = true;
        }

        return $retorno;
    }

    public function SetNombreCliente($nombreCliente)
    {
        $retorno = false;
        if (is_string($nombreCliente) && $nombreCliente != '') {
            $this->nombreCliente = $nombreCliente;
            $retorno = true;
        }

        return $retorno;
    }

    public function SetFecha($fecha)
    {
        $retorno = false;
        if (is_int($fecha)) {
            $this->fecha = $fecha;
            $retorno = true;
        }

        return $retorno;
    }

    public function SetImporte($importe)
    {
        $return = false;
        $this->importe = $importe;
        $return = true;

        return $return;
    }

}
