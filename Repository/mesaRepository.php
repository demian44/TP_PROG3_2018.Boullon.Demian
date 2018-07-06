<?php

class MesaRepository
{
    public static function GetAll()
    {

        $arrayMesa = [];
        try {
            $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
            $mysqlQuery = 'SELECT * FROM mesas WHERE active = 1 ';
            
            $consulta = $objetoAccesoDato->RetornarConsulta($mysqlQuery);
            
            $row = $consulta->execute();
            $lastOrderId = -1;
            
            foreach ($consulta->fetchAll() as $row) {
                $mesa = new Mesa();
                $mesa->SetId($row["id"]);
                $mesa->SetCode($row["code"]);
                $mesa->SetStatus($row["status"]);
                array_push($arrayMesa, $mesa->ToJson());
                
            }

            $return = new ApiResponse(REQUEST_ERROR_TYPE::NOERROR, $arrayMesa);

        } catch (PDOException $exception) {
            throw $exception;
        } catch (Exception $exception) {
            throw $exception;
        }

        return $return;
    }

    /**
     * Agregar Throw.
     */
    public static function Insert(Mesa $mesa)
    {
        try {
            $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();

            $consulta = $objetoAccesoDato->RetornarConsulta('INSERT INTO mesas (code) '
                . 'VALUES(:code)');
            $consulta->bindValue(':code', $mesa->GetCode(), PDO::PARAM_STR);

            if (!$consulta->execute()) { //Si no retorna 1 no guardó el elemento
                $response = new ApiResponse(REQUEST_ERROR_TYPE::DATABASE,
                    'Error al guardar la mesa en la base de datos.');
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

    /**
     * Chequea si los ids cargados estan cargados en la base de items.
     **/
    public static function CheckId(int $id)
    {
        $return = false;

        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $objetoAccesoDato->RetornarConsulta("SELECT id FROM mesas WHERE id in (:id)");
        $consulta->bindValue(':id', $id(), PDO::PARAM_INT);
        $consulta->execute();
        $rows = $consulta->fetch();
        if ($row) {
            $return = true;
        }

        return $return;
    }
    /**
     * Chequea si los ids cargados estan cargados en la base de items.
     **/
    public static function GetById(int $id)
    {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $objetoAccesoDato->RetornarConsulta("SELECT id FROM mesas WHERE id in (:id)");
        $consulta->bindValue(':id', $id(), PDO::PARAM_INT);
        $consulta->execute();
        $rows = $consulta->fetch();
        if ($row) {
            $mesa = new Mesa();
            $mesa->SetCode($row["code"]);
            $mesa->SetId($row["id"]);
            $mesa->SetStatus($row["status"]);
        }

        return $return;
    }

    public static function CheckCodes(string $code)
    {
        $return = false;
        try {
            $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();

            $consulta = $objetoAccesoDato->RetornarConsulta('SELECT id FROM mesas WHERE code = :code');
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

    public static function Edit(Mesa $mesa)
    {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();

        $consulta = $objetoAccesoDato->RetornarConsulta("UPDATE mesas set code = :code WHERE id = :id");

        $consulta->bindValue(':id', $mesa->GetId(), PDO::PARAM_INT);
        $consulta->bindValue(':code', $mesa->GetCode(), PDO::PARAM_STR);

        return $consulta->execute();
    }

    public static function Delete(int $id)
    {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();

        $consulta = $objetoAccesoDato->RetornarConsulta("UPDATE mesas set active=0 WHERE id = :id");

        $consulta->bindValue(':id', $id, PDO::PARAM_INT);

        return $consulta->execute();
    }
}
