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
                $mesa->SetStatus(MESA_STATUS::String($row["status"]));
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
        $consulta->bindValue(':id', $id, PDO::PARAM_INT);
        $consulta->execute();
        $row = $consulta->fetch();
        if ($row) {
            $mesa = new Mesa();
            $mesa->SetCode($row["code"]);
            $mesa->SetId($row["id"]);
            $mesa->SetStatus($row["status"]);
        }

        return $return;
    }

    public static function IsFree(int $id): bool
    {
        $return = false;
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $objetoAccesoDato->RetornarConsulta('SELECT id FROM mesas WHERE id in (:id)
         AND status = 0');
        $consulta->bindValue(':id', $id, PDO::PARAM_INT);
        $consulta->execute();

        $row = $consulta->fetch();
        if ($row) {
            $return = true;
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

            if ($status == MESA_STATUS::CON_CLIENTES_PAGANDO) {
                $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
                $consulta = $objetoAccesoDato->RetornarConsulta("UPDATE orders set status = " .
                    ":status WHERE mesa_id = :id AND status = 3");
                $consulta->bindValue(':id', $id, PDO::PARAM_INT);
                $consulta->bindValue(':status', ORDER_STATUS::EATED, PDO::PARAM_INT);
                $consulta->execute();

                $consulta = $objetoAccesoDato->RetornarConsulta("UPDATE order_item
                set order_item.status = :status
                WHERE order_item.order_id = (SELECT  MAX(id)  FROM orders WHERE orders.mesa_id = :id)");
                $consulta->bindValue(':id', $id, PDO::PARAM_INT);
                $consulta->bindValue(':status', ORDER_STATUS::EATED, PDO::PARAM_INT);
                $consulta->execute();
            }

            if ($status == MESA_STATUS::CERRADA) {
                $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
                $consulta = $objetoAccesoDato->RetornarConsulta("UPDATE orders set status = " .
                    ORDER_STATUS::CLOSE . " WHERE mesa_id = :id AND status = 4");
                $consulta->bindValue(':id', $id, PDO::PARAM_INT);
                $consulta->execute();
                $consulta = $objetoAccesoDato->RetornarConsulta("UPDATE order_item
                set order_item.status = :status
                WHERE order_item.order_id = (SELECT MAX(id) FROM orders WHERE orders.mesa_id = :id )");
                $consulta->bindValue(':id', $id, PDO::PARAM_INT);
                $consulta->bindValue(':status', ORDER_STATUS::EATED, PDO::PARAM_INT);
                $consulta->execute();
            }

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
    public static function GetMaxAndMinRepetitions()
    {
        try {
            $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
            $mysqlQuery = 'SELECT COUNT(*) total, mesas.id,mesas.code FROM mesas
            INNER JOIN orders ON mesas.id = orders.mesa_id
            GROUP BY mesas.id';
            $consulta = $objetoAccesoDato->RetornarConsulta($mysqlQuery);
            $consulta->execute();
            $max;
            $min;
            $flag = true;
            foreach ($consulta->fetchAll() as $row) {
                if ($flag) {
                    $flag = false;
                    $max["total"] = $row["total"];
                    $max["id"] = $row["id"];
                    $max["code"] = $row["code"];
                    $min["total"] = $row["total"];
                    $min["id"] = $row["id"];
                    $min["code"] = $row["code"];
                } else {
                    if ($max["total"] < $row["total"]) {
                        $max["total"] = $row["total"];
                        $max["id"] = $row["id"];
                        $max["code"] = $row["code"];
                    }
                    if ($min["total"] > $row["total"]) {
                        $min["total"] = $row["total"];
                        $min["id"] = $row["id"];
                        $min["code"] = $row["code"];
                    }
                }
            }
            $result["masUsada"] = $max;
            $result["menosUsada"] = $min;
            $masMenosFacturo = Self::MasMenosFacturo();
            $result["masFacturo"] = $masMenosFacturo["masFacturo"];
            $result["menosFacturo"] = $masMenosFacturo["menosFacturo"];

            $conMaximaMminimaFactura = Self::MesaConMaximaMinimaFactura();

            $result["conMaximaFactura"] = $conMaximaMminimaFactura["conMaximaFactura"];
            $result["conMinimaFactura"] = $conMaximaMminimaFactura["conMinimaFactura"];

            $result["mejoresComentarios"] = Self::MejoresComentarios();

            $return = new ApiResponse(REQUEST_ERROR_TYPE::NOERROR, $result);

        } catch (PDOException $exception) {
            throw $exception;
        } catch (Exception $exception) {
            throw $exception;
        }

        return $return;
    }
    private static function MasMenosFacturo()
    {
        try {
            $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
            $mysqlQuery = 'SELECT SUM(items.precio) as total, mesas.id as id,
            mesas.code as code  FROM items
            INNER JOIN order_item ON order_item.item_id = 				items.id
            INNER JOIN orders ON orders.id = 			order_item.order_id
            INNER JOIN mesas ON orders.mesa_id = mesas.id
            WHERE mesas.id = 1
            AND orders.ordered_time <
            AND orders.ordered_time > ';
            $consulta = $objetoAccesoDato->RetornarConsulta($mysqlQuery);
            $consulta->execute();
            $max;
            $min;
            $flag = true;
            foreach ($consulta->fetchAll() as $row) {
                if ($flag) {
                    $flag = false;
                    $max["total"] = $row["total"];
                    $max["id"] = $row["id"];
                    $max["code"] = $row["code"];
                    $min["total"] = $row["total"];
                    $min["id"] = $row["id"];
                    $min["code"] = $row["code"];
                } else {
                    if ($max["total"] < $row["total"]) {
                        $max["total"] = $row["total"];
                        $max["id"] = $row["id"];
                        $max["code"] = $row["code"];
                    }
                    if ($min["total"] > $row["total"]) {
                        $min["total"] = $row["total"];
                        $min["id"] = $row["id"];
                        $min["code"] = $row["code"];
                    }
                }
            }
            $result["masFacturo"] = $max;
            $result["menosFacturo"] = $min;

            $return = $result;

        } catch (PDOException $exception) {
            throw $exception;
        } catch (Exception $exception) {
            throw $exception;
        }

        return $return;
    }

    private static function MesaConMaximaMinimaFactura()
    {
        try {
            $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
            $mysqlQuery = 'SELECT SUM(items.precio) as total, mesas.id as id,
            mesas.code as code  FROM items
            INNER JOIN order_item ON order_item.item_id = items.id
            INNER JOIN orders ON orders.id = order_item.order_id
            INNER JOIN mesas ON orders.mesa_id = mesas.id
            GROUP BY orders.id';
            $consulta = $objetoAccesoDato->RetornarConsulta($mysqlQuery);
            $consulta->execute();
            $max;
            $min;
            $flag = true;
            foreach ($consulta->fetchAll() as $row) {
                if ($flag) {
                    $flag = false;
                    $max["total"] = $row["total"];
                    $max["id"] = $row["id"];
                    $max["code"] = $row["code"];
                    $min["total"] = $row["total"];
                    $min["id"] = $row["id"];
                    $min["code"] = $row["code"];
                } else {
                    if ($max["total"] < $row["total"]) {
                        $max["total"] = $row["total"];
                        $max["id"] = $row["id"];
                        $max["code"] = $row["code"];
                    }
                    if ($min["total"] > $row["total"]) {
                        $min["total"] = $row["total"];
                        $min["id"] = $row["id"];
                        $min["code"] = $row["code"];
                    }
                }
            }
            $result["conMaximaFactura"] = $max;
            $result["conMinimaFactura"] = $min;

            $return = $result;

        } catch (PDOException $exception) {
            throw $exception;
        } catch (Exception $exception) {
            throw $exception;
        }

        return $return;
    }
    private static function MejoresComentarios()
    {
        $comentarios = [];
        try {
            $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
            $mysqlQuery = 'SELECT order_evaluation.comentario,mesas.code,mesas.id FROM order_evaluation
            INNER JOIN orders ON order_evaluation.order_code = orders.code
            INNER JOIN mesas ON mesas.id = orders.mesa_id
            WHERE order_evaluation.mesa_evaluation > 6';

            $consulta = $objetoAccesoDato->RetornarConsulta($mysqlQuery);
            $consulta->execute();

            $flag = true;
            foreach ($consulta->fetchAll() as $row) {
                $comentario["comentario"] = $row["comentario"];
                $comentario["id"] = $row["id"];
                $comentario["code"] = $row["code"];
                array_push($comentarios, $comentario);
            }

        } catch (PDOException $exception) {
            throw $exception;
        } catch (Exception $exception) {
            throw $exception;
        }

        return $comentarios;
    }
    public static function FacturadoEntreFechas($from, $to, $id)
    {
        $comentarios = [];
        try {
            $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
            $mysqlQuery = 'SELECT SUM(items.precio) as total, mesas.id as id,
            mesas.code as code  FROM items
            INNER JOIN order_item ON order_item.item_id = 				items.id
            INNER JOIN orders ON orders.id = 			order_item.order_id
            INNER JOIN mesas ON orders.mesa_id = mesas.id
            AND orders.ordered_time < :to
            AND orders.ordered_time > :from
            AND mesas.id = :id';
            $consulta = $objetoAccesoDato->RetornarConsulta($mysqlQuery);

            $consulta->bindValue(':to', $to, PDO::PARAM_STR);
            $consulta->bindValue(':from', $from, PDO::PARAM_STR);
            $consulta->bindValue(':id', $id, PDO::PARAM_INT);
            $consulta->execute();
            $row = $consulta->fetch();
            if ($row && $row["total"] != null) {
                $mesa["total"] = $row["total"];
                $mesa["id"] = $row["id"];
                $mesa["code"] = $row["code"];
                $return = new ApiResponse(REQUEST_ERROR_TYPE::NOERROR, $mesa);
            } else {
                $return = new ApiResponse(REQUEST_ERROR_TYPE::NOERROR, "No se encuentra facturacion entre esas fechas");
            }

        } catch (PDOException $exception) {
            throw $exception;
        } catch (Exception $exception) {
            throw $exception;
        }

        return $return;
    }
}
