<?php

class VentaRepository
{
    /**
     * Agregar Throw.
     */
    public function InsertVenta($venta)
    {
        try {
            $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
            $consulta = $objetoAccesoDato->RetornarConsulta('INSERT INTO ventas (color,marca,precio,talle,foto)'
                . 'VALUES(:color,:marca,:precio,:talle,:foto)');
            $consulta->bindValue(':color', $venta->GetColor(), PDO::PARAM_STR);
            $consulta->bindValue(':marca', $venta->GetMarca(), PDO::PARAM_STR);
            $consulta->bindValue(':precio', $venta->GetPrecio(), PDO::PARAM_STR);
            $consulta->bindValue(':talle', $venta->GetTalle(), PDO::PARAM_STR);
            $consulta->bindValue(':foto', $venta->GetFoto(), PDO::PARAM_INT);
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
    public function DeletetVenta($id)
    {
        try {
            $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();

            $consulta = $objetoAccesoDato->RetornarConsulta('DELETE FROM ventas WHERE id = :id');
            $we = $consulta->execute(array(':id' => $id));
            echo $we;
            $response = new ApiResponse(REQUEST_ERROR_TYPE::NOERROR, 'EXITO');

            // if (!$consulta->execute(array(':id' => $id))) { //Si no retorna 1 no guardó el elemento
            //     $response = new ApiResponse(REQUEST_ERROR_TYPE::DATABASE, 'Error al borrar la venta.');
            // } else {
            //     $response = new ApiResponse(REQUEST_ERROR_TYPE::NOERROR, 'EXITO');
            // }
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

            $consulta = $objetoAccesoDato->RetornarConsulta('SELECT id FROM ventas WHERE code = :code');
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

            $consulta = $objetoAccesoDato->RetornarConsulta('SELECT * FROM ventas');
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

    public static function TraerVentaPorId($id)
    {

        $arrayVentas = [];
        try {
            $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();

            $consulta = $objetoAccesoDato->RetornarConsulta('SELECT * FROM ventas where id = :id');
            $row = $consulta->execute(array(':id' => $id));

            foreach ($consulta->fetchAll() as $row) {
                array_push($arrayVentas, $row);
            }

        } catch (PDOException $exception) {
            throw $exception;
        } catch (Exception $exception) {
            throw $exception;
        }

        return $arrayVentas;
    }

}
