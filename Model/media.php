<?php
class Media extends Foto
{
    private $color;
    private $marca;
    private $precio;
    private $talle;
    private $foto;

    public function __construct($color, $marca, $talle, $precio)
    {
        $this->color = $color;
        $this->marca = $marca;
        $this->talle = $talle;
        $this->precio = $precio;
    }

    /// Getters
    public function GetColor()
    {
        return $this->color;
    }

    public function GetPrecio()
    {
        return $this->precio;
    }

    public function GetMarca()
    {
        return $this->marca;
    }

    public function GetTalle()
    {
        return $this->talle;
    }

    public function GetFoto()
    {
        return $this->foto;
    }

    // End Getters

    ///Setters
    public function SetColor($color)
    {
        $retorno = false;
        if (is_string($color) && $color != '') {
            $this->color = $color;
            $retorno = true;
        }

        return $retorno;
    }

    public function SetMarca($marca)
    {
        $retorno = false;
        if (is_string($marca) && $marca != '') {
            $this->marca = $marca;
            $retorno = true;
        }

        return $retorno;
    }

    public function SetTalle($talle)
    {
        $retorno = false;
        if (is_int($talle)) {
            $this->talle = $talle;
            $retorno = true;
        }

        return $retorno;
    }

    public function SetFoto($foto)
    {
        $retorno = false;
        //if (is_int($foto)) {
        $this->foto = $foto;
        $retorno = true;
        //}

        return $retorno;
    }

    public function SetPrecio($precio)
    {
        $return = false;
        $this->precio = $precio;
        $return = true;

        return $return;
    }

}
