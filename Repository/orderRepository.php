<?php

class OrderRepository
{
    /**
     * Agregar Throw.
     */
    public function InsertOrder($order)
    {
        $response = new InternalResponse();
        $response->SetMessege('Carga exitosa');
        try {
            $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
            $consulta = $objetoAccesoDato->RetornarConsulta('INSERT INTO orders (client_name,code,ordered_time,mesa_id)'
            .'VALUES(:client_name,:code,:ordered_time,:mesa_id)');
            $consulta->bindValue(':client_name', $order->GetCliente(), PDO::PARAM_STR);
            $consulta->bindValue(':code', $order->GetCode(), PDO::PARAM_STR);
            $consulta->bindValue(':ordered_time', $order->GetOrderedTime(), PDO::PARAM_STR);
            $consulta->bindValue(':mesa_id', $order->GetMesaId(), PDO::PARAM_INT);

            if (!$consulta->execute()) { //Si no retorna 1 no guardó el elemento
                $response->SetMessege('Error al guardar la orden en la base de datos.');
                $response->SetError(true);
            }
        } catch (PDOException $exception) {
            throw $exception;
        } catch (Exception $exception) {
            throw $exception;
        }

        return $response;
    }

    public static function CheckCodes($code)
    {
        $return;
        try {
            $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();

            $consulta = $objetoAccesoDato->RetornarConsulta('SELECT id FROM orders WHERE code = :code');
            $consulta->execute(array(':code' => $code));
            $row = $consulta->fetch();
            if ($row) {
                $return = true;
            } else {
                $return = false;
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
