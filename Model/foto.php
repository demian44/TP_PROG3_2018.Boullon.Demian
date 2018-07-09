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
        if (is_string($foto)) {
            $this->foto = $foto;
            $retorno = true;
        }

        return $retorno;
    }

    
    public static function SaveFoto($file, $name, $destino)
    {
        ////GUARDAR ARCHIVO
        $nombreAnterior = $file['foto']->getClientFilename();
        $extension = explode('.', $nombreAnterior);

        $file['foto']->moveTo($destino . "$name." . $extension[1]);
        return substr($destino, 2) . "$name." . $extension[1];
    }

    public static function BackupFoto($file, $path)
    {
        $return = false;
        $fileName = explode("/", $file);
        $file = trim($file);
        $nameFile = trim($fileName[count($fileName) - 1]);
        $pathFile = $path . $nameFile;
        $arrayFileName = explode(".", $nameFile);
        $return = true;
        copy($pathFile, "./backUp/" . $nameFile);
        unlink($pathFile);
        return $return;
    }
}
