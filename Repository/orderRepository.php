<?php

class OrderRepository
{
    /**
     * Agregar Throw.
     */
    public static function InsertOrder(Order $order)
    {
        try {
            $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();

            $consulta = $objetoAccesoDato->RetornarConsulta('INSERT INTO orders '
                . '(client_name,code,ordered_time,mesa_id) '
                . 'VALUES(:client_name,:code,:ordered_time,:mesa_id)');

            $consulta->bindValue(':client_name', $order->GetCliente(), PDO::PARAM_STR);
            $consulta->bindValue(':code', $order->GetCode(), PDO::PARAM_STR);
            $consulta->bindValue(':ordered_time', $order->GetOrderedTime(), PDO::PARAM_STR);
            $consulta->bindValue(':mesa_id', $order->GetMesaId(), PDO::PARAM_INT);

            if (!$consulta->execute()) { //Si no retorna 1 no guardó el elemento
                $response = new ApiResponse(REQUEST_ERROR_TYPE::DATABASE, 'Error al guardar la orden en la base de datos.');
            } else {
                $consulta = $objetoAccesoDato->RetornarConsulta('SELECT MAX(id) FROM orders');
                $consulta->execute();
                $row = $consulta->fetch();
                Self::SaveOrderItems($order, $row[0]);
                $response = new ApiResponse(REQUEST_ERROR_TYPE::NOERROR, 'EXITO');
            }
        } catch (PDOException $exception) {
            throw $exception;
        } catch (Exception $exception) {
            throw $exception;
        }

        return $response;
    }

    private static function SaveOrderItems(Order $order, int $orderId)
    {
        $items =  $order->GetItems();
        
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        foreach ($items as $key => $item) {
            $mysqlQuery = ' INSERT INTO order_item (item_id,cant,order_id) '
                . 'VALUES( '.$item->GetItemId().' , '.$item->GetCant().' , '.$orderId.' );';
                $consulta = $objetoAccesoDato->RetornarConsulta($mysqlQuery);
                $consulta->execute();
        }
    }

    /**
     * Chequea si los ids cargados estan cargados en la base de items.
     **/
    public static function CheckItems(array $items)
    {
        $idsFaltantes = [];
        $idsEncontrados = [];

        $mysqlQuery = "SELECT id FROM items WHERE id in (";
        foreach ($items as $key => $item) {
            if ($key > 0) {
                $mysqlQuery .= ",";
            }
            $mysqlQuery .= " " . $item;
        }
        $mysqlQuery .= ");";

        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $objetoAccesoDato->RetornarConsulta($mysqlQuery);
        $consulta->execute();
        $rows = $consulta->fetchAll();

        foreach ($rows as $row) {
            array_push($idsEncontrados, $row[0]);
        }

        foreach ($items as $key => $item) {
            if (array_search($item, $idsEncontrados) === false) {
                array_push($idsFaltantes, $item);
            }

        }

        return $idsFaltantes;
    }

    public static function CheckCodes($code)
    {
        $return = false;
        try {
            $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();

            $consulta = $objetoAccesoDato->RetornarConsulta('SELECT id FROM orders WHERE code = :code');
            $consulta->execute(array(':code' => $code));
            $row = $consulta->fetch();
            if ($row) {
                $return = true;
            }
        } catch (PDOException $exception) {
            throw $exception;
        } catch (Exception $exception) {
            throw $exception;
        }

        return $return;
    }

    public static function ModificarPedido($id, $cliente, $sexo, $cantante)
    {
    }

    public static function EliminarPedido($id)
    {
        // $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();

        // $consulta = $objetoAccesoDato->RetornarConsulta("DELETE FROM cds WHERE id = :id");

        // $consulta->bindValue(':id', $id, PDO::PARAM_INT);

        // return $consulta->execute();
    }
}
