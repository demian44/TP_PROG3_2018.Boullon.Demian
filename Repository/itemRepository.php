<?php

class ItemRepository
{
    public static function GetAll()
    {

        $arrayMesa = [];
        try {
            $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
            $mysqlQuery = 'SELECT * FROM items WHERE active = 1 ';
            
            $consulta = $objetoAccesoDato->RetornarConsulta($mysqlQuery);
            
            $row = $consulta->execute();
            $lastOrderId = -1;
            $array = [];
            foreach ($consulta->fetchAll() as $row) {
                
                $item["id"]= $row["id"];
                $item["name"]= $row["name"];
                $item["precio"]= $row["precio"];
                $item["sector"]= ITEM_TYPE::String($row["sector"]);
                $item["sectorNumeric"]= $row["sector"];
                array_push($array, $item);
            }
            $return = new ApiResponse(REQUEST_ERROR_TYPE::NOERROR, $array);

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
    public static function Save(Item $item)
    {
        try {
            $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
            $item->GetName();
            $item->GetSector();
            $consulta = $objetoAccesoDato->RetornarConsulta('INSERT INTO items (name,sector,precio) '
                . 'VALUES(:name,:sector,:precio)');
            $consulta->bindValue(':name', $item->GetName(), PDO::PARAM_STR);
            $consulta->bindValue(':sector', $item->GetSector(), PDO::PARAM_STR);
            $consulta->bindValue(':precio', $item->GetPrecio(), PDO::PARAM_STR);

            if (!$consulta->execute()) { //Si no retorna 1 no guardó el elemento
                $response = new ApiResponse(REQUEST_ERROR_TYPE::DATABASE,
                    'Error al guardar el item en la base de datos.');
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
    public static function GetCodeById(int $id)
    {
        $return = false;

        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $objetoAccesoDato->RetornarConsulta("SELECT code FROM mesas WHERE id in (:id)");
        $consulta->bindValue(':id', $id, PDO::PARAM_INT);
        $consulta->execute();
        $row = $consulta->fetch();
        if ($row) {
            $return = $row["code"];
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

    public static function SetStatus(int $id, int $status): ApiResponse
    {
        $response;
        try {
            $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
            $consulta = $objetoAccesoDato->RetornarConsulta("UPDATE mesas set status = :status WHERE id = :id");
            $consulta->bindValue(':id', $id, PDO::PARAM_INT);
            $consulta->bindValue(':status', $status, PDO::PARAM_INT);
            $consulta->execute();
            $response = new ApiResponse(REQUEST_ERROR_TYPE::NOERROR, 'EXITO');
        } catch (PDOException $exception) {
            throw $exception;
        } catch (Exception $exception) {
            throw $exception;
        }
        return $response;
    }

    public static function Delete(int $id)
    {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();

        $consulta = $objetoAccesoDato->RetornarConsulta("UPDATE mesas set active=0 WHERE id = :id");

        $consulta->bindValue(':id', $id, PDO::PARAM_INT);

        return $consulta->execute();
    }
}
