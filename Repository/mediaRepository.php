<?php

class MediaRepository
{

    public static function Insert($media)
    {

        try {
            $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
            $consulta = $objetoAccesoDato->RetornarConsulta('INSERT INTO medias (color,marca,precio,talle,foto)'
                . 'VALUES(:color,:marca,:precio,:talle,:foto)');
            $consulta->bindValue(':color', $media->GetColor(), PDO::PARAM_STR);
            $consulta->bindValue(':marca', $media->GetMarca(), PDO::PARAM_STR);
            $consulta->bindValue(':precio', $media->GetPrecio(), PDO::PARAM_STR);
            $consulta->bindValue(':talle', $media->GetTalle(), PDO::PARAM_STR);
            $consulta->bindValue(':foto', $media->GetFoto(), PDO::PARAM_INT);
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
    public static function Delete($id)
    {
        try {
            $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();

            $consulta = $objetoAccesoDato->RetornarConsulta('UPDATE medias SET active= 0 ' .
                ' WHERE id = :id');
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

            $consulta = $objetoAccesoDato->RetornarConsulta(
                'SELECT id FROM medias  WHERE  code = :code AND active= 1');
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

    public static function TraerMedias(&$arrayMedias)
    {
        $return = false;
        $arrayMedias = [];
        try {
            $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();

            $consulta = $objetoAccesoDato->RetornarConsulta('SELECT * FROM medias WHERE  active = 1');
            $row = $consulta->execute();

            foreach ($consulta->fetchAll() as $row) {
                $media["color"] = $row["color"];
                $media["marca"] = $row["marca"];
                $media["talle"] = $row["talle"];
                $media["precio"] = $row["precio"];
                $media["id"] = $row["id"];
                array_push($arrayMedias, $media);

                $return = true;
            }

        } catch (PDOException $exception) {
            throw $exception;
        } catch (Exception $exception) {
            throw $exception;
        }

        return $return;
    }

    public static function TraerMediaPorId($id)
    {

        $arrayMedias = [];
        try {
            $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();

            $consulta = $objetoAccesoDato->RetornarConsulta('SELECT * FROM medias where id = :id ' .
                ' AND active = 1');

            $consulta->execute(array(':id' => $id));
            $row = $consulta->fetch();
            if ($row) {
                $media["id"] = $row["id"];
                $media["color"] = $row["color"];
                $media["marca"] = $row["marca"];
                $media["precio"] = $row["precio"];
                $media["talle"] = $row["talle"];
                $media["foto"] = $row["foto"];
            } else {
                $media = false;
            }

        } catch (PDOException $exception) {
            throw $exception;
        } catch (Exception $exception) {
            throw $exception;
        }

        return $media;
    }

    public static function TraerPorId($id)
    {
        $return;
        try {
            $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();

            $consulta = $objetoAccesoDato->RetornarConsulta('SELECT * FROM medias WHERE id = :id AND active=1');
            $consulta->execute(array(':id' => $id));
            $row = $consulta->fetch();
            if ($row) {
                $media = new Media($row['color'], $row['marca'], $row['talle'], $row['precio']);
                $media->SetId('id');
                $return = $media;
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
