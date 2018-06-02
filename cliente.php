<?php
class Cliente
{
    private $name;
    private $idCliente;

    public function GetName($name)
    {
        if (!is_null($name) && $name != null)
            $this->name = $name;
    }

    public function MostrarDatos()
    {
        return $this->nombre . " - " . $this->sexo;
    }

    public static function TraerClienteNacionalidadSexoArray()
    {
        /*$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();

        $consulta = $objetoAccesoDato->RetornarConsulta("SELECT nombre, nacionalidad, "
            . "sexo FROM clientes WHERE nacionalidad = :nacionalidad "
            . "AND sexo= :sexo");

        $consulta->execute(array(":nacionalidad" => $nacionalidad, ":sexo" => $sexo));
        $array = [];
        foreach ($consulta->fetchAll() as $row) {
            array_push($array, $row);
        }

        return $array;/*/
    }

    /**
     * Agregar Throw
     */
    public function InsertarElClienteParametros()
    {
        $response = new InternalResponse();
        $response->SetMessege("Carga exitosa");

        try {
            $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();

            $consulta = $objetoAccesoDato->RetornarConsulta("INSERT INTO cliente (nombre)"
                . "VALUES(:nombre)");
            $consulta->bindValue(':nombre', $this->name, PDO::PARAM_STR);
            echo "\n";
            if (!$consulta->execute()) //Si no retorna 1 no guardó el elemento
                $response->SetMessege("Error al guardar al cliente en la base de datos.");

        } catch (PDOException $exception) {
            $response->SetMessege("Error: " . $exception->getMessage());
        } catch (Exception $exception) {
            $response->SetMessege("Error: " . $exception->getMessage());
        }
        return $response;
    }

    public static function ModificarCliente($id, $nombre, $sexo, $cantante)
    {

        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();

        $consulta = $objetoAccesoDato->RetornarConsulta("UPDATE cds SET titel = :nombre, interpret = :cantante, 
                                                        jahr = :sexo WHERE id = :id");

        $consulta->bindValue(':id', $id, PDO::PARAM_INT);
        $consulta->bindValue(':nombre', $nombre, PDO::PARAM_INT);
        $consulta->bindValue(':sexo', $sexo, PDO::PARAM_INT);
        $consulta->bindValue(':cantante', $cantante, PDO::PARAM_STR);

        return $consulta->execute();

    }

    public static function EliminarCliente($id)
    {

        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();

        $consulta = $objetoAccesoDato->RetornarConsulta("DELETE FROM cds WHERE id = :id");

        $consulta->bindValue(':id', $id, PDO::PARAM_INT);

        return $consulta->execute();

    }

}