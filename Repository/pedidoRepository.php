<?php
class PedidoRepository
{

    public static function TraerPedido()
    {
        /*$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();

        $consulta = $objetoAccesoDato->RetornarConsulta("SELECT nombre, nacionalidad, "
            . "sexo FROM pedidos WHERE nacionalidad = :nacionalidad "
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
    public function InsertarPedido($pedido)
    {
        $response = new InternalResponse();
        $response->SetMessege("Carga exitosa");
        try {
            $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();

             $consulta = $objetoAccesoDato->RetornarConsulta("INSERT INTO pedidos (cliente)"
                 . "VALUES(:cliente)");
             $consulta->bindValue(':cliente', $pedido->GetCliente(), PDO::PARAM_STR);
             if (!$consulta->execute()) //Si no retorna 1 no guardó el elemento
             $response->SetMessege("Error al guardar al pedido en la base de datos.");

        } catch (PDOException $exception) {
            $response->SetMessege("Error: " . $exception->getMessage());
        } catch (Exception $exception) {
            $response->SetMessege("Error: " . $exception->getMessage());
        }
        return $response;

    }
    public static function ModificarPedido($id, $cliente, $sexo, $cantante)
    {

        // $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();

        // $consulta = $objetoAccesoDato->RetornarConsulta("UPDATE cds SET titel = :nombre, interpret = :cantante, 
        //                                                 jahr = :sexo WHERE id = :id");

        // $consulta->bindValue(':id', $id, PDO::PARAM_INT);
        // $consulta->bindValue(':nombre', $nombre, PDO::PARAM_INT);
        // $consulta->bindValue(':sexo', $sexo, PDO::PARAM_INT);
        // $consulta->bindValue(':cantante', $cantante, PDO::PARAM_STR);

        // return $consulta->execute();

    }

    public static function EliminarPedido($id)
    {

        // $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();

        // $consulta = $objetoAccesoDato->RetornarConsulta("DELETE FROM cds WHERE id = :id");

        // $consulta->bindValue(':id', $id, PDO::PARAM_INT);

        // return $consulta->execute();

    }

}