<?php

class VentaRepository
{
    public static function InsertVenta($venta)
    {

        try {
            $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
            $consulta = $objetoAccesoDato->RetornarConsulta(
                'INSERT INTO ventas (idMedia,nombreCliente,fecha,importe,foto)'
                . 'VALUES(:idMedia,:nombreCliente,:fecha,:importe,:foto)');

            $consulta->bindValue(':idMedia', $venta->GetIdMedia(), PDO::PARAM_INT);
            $consulta->bindValue(':nombreCliente', $venta->GetNombreCliente(), PDO::PARAM_STR);
            $consulta->bindValue(':fecha', $venta->GetFecha(), PDO::PARAM_STR);
            $consulta->bindValue(':importe', $venta->GetImporte(), PDO::PARAM_STR);
            $consulta->bindValue(':foto', $venta->GetFoto(), PDO::PARAM_STR);

            if (!$consulta->execute()) { //Si no retorna 1 no guardó el elemento
                $response = new ApiResponse(REQUEST_ERROR_TYPE::DATABASE, 'Error al guardar la orden en la base de datos.');
            } else {
                $response = new ApiResponse(REQUEST_ERROR_TYPE::NOERROR, 'EXITO');
            }
        } catch (PDOException $exception) {
            throw $exception;
        } catch (Exception $exception) {
            throw $exception;
        }

        return $response;
    }

    public static function EditarVenta($venta)
    {

        try {
            $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
            $consulta = $objetoAccesoDato->RetornarConsulta(
                ' UPDATE ventas SET idMedia = :idMedia, nombreCliente = :nombreCliente, fecha = :fecha,importe=:importe,foto=:foto'
                . ' WHERE id = :id');

            $consulta->bindValue(':idMedia', $venta->GetIdMedia(), PDO::PARAM_INT);
            $consulta->bindValue(':nombreCliente', $venta->GetNombreCliente(), PDO::PARAM_STR);
            $consulta->bindValue(':fecha', $venta->GetFecha(), PDO::PARAM_STR);
            $consulta->bindValue(':importe', $venta->GetImporte(), PDO::PARAM_STR);
            $consulta->bindValue(':foto', $venta->GetFoto(), PDO::PARAM_STR);
            $consulta->bindValue(':id', $venta->GetId(), PDO::PARAM_INT);

            if (!$consulta->execute()) { //Si no retorna 1 no guardó el elemento
                $response = new ApiResponse(REQUEST_ERROR_TYPE::DATABASE, 'Error al editar la orden en la base de datos.');
            } else {
                $response = new ApiResponse(REQUEST_ERROR_TYPE::NOERROR, 'EXITO');
            }
        } catch (PDOException $exception) {
            throw $exception;
        } catch (Exception $exception) {
            throw $exception;
        }

        return $response;
    }
    public static function DeletetVenta($id)
    {
        try {
            $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();

            $consulta = $objetoAccesoDato->RetornarConsulta('UPDATE ventas SET active=0 WHERE id = :id');
            $consulta->execute(array(':id' => $id));
            $response = new ApiResponse(REQUEST_ERROR_TYPE::NOERROR, 'EXITO');

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

            $consulta = $objetoAccesoDato->RetornarConsulta('SELECT id FROM ventas ' .
                ' WHERE code = :code AND active = 1');
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

    public static function TraerVentas(&$arrayVentas)
    {
        $return = false;
        $arrayVentas = [];
        try {
            $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();

            $consulta = $objetoAccesoDato->RetornarConsulta('SELECT * FROM ventas ' .
                ' AND active = 1');
            $row = $consulta->execute();

            foreach ($consulta->fetchAll() as $row) {
                array_push($arrayVentas, $row);
                $return = true;
            }

        } catch (PDOException $exception) {
            throw $exception;
        } catch (Exception $exception) {
            throw $exception;
        }

        return $return;
    }

    public static function TraerPorId($id)
    {
        $return;
        try {
            $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();

            $consulta = $objetoAccesoDato->RetornarConsulta('SELECT * FROM ventas  WHERE id = :id ' .
                ' AND active = 1');
            $consulta->execute(array(':id' => $id));
            $row = $consulta->fetch();
            if ($row) {
                $venta = new Venta($row['idMedia'], $row['nombreCliente'], $row['fecha'], $row['importe']);
                $venta->SetId($row['id']);
                $venta->SetFoto($row['foto']);
                $return = $venta;
            } else {
                $return = null;
            }
        } catch (PDOException $exception) {
            throw $exception;
        } catch (Exception $exception) {
            throw $exception;
        }

        return $return;
    }

}
