<?php
class Foto extends Entity
{
    private $foto;

    public function __construct($color, $marca, $talle, $precio)
    {
        $this->color = $color;
        $this->marca = $marca;
        $this->talle = $talle;
        $this->precio = $precio;
    }

    public function GetFoto()
    {
        return $this->foto;
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

    public static function BackupFoto($file)
    {
        $fileName = explode("/", $file);
        $file = trim($file);
        $nameFile = trim($fileName[count($fileName) - 1]);
        $pathFile = "./imgs/" . $nameFile;
        $arrayFileName = explode(".", $nameFile);
        copy($pathFile, "backUp/" . $nameFile);
        unlink($pathFile);
    }
}
