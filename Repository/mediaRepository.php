<?php

class MediaRepository
{
    /**
     * Agregar Throw.
     */
    public function InsertMedia($media)
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
    public function DeletetMedia($id)
    {
        try {
            $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();

            $consulta = $objetoAccesoDato->RetornarConsulta('DELETE FROM medias WHERE id = :id');
            $we = $consulta->execute(array(':id' => $id));
            echo $we;
            $response = new ApiResponse(REQUEST_ERROR_TYPE::NOERROR, 'EXITO');

            // if (!$consulta->execute(array(':id' => $id))) { //Si no retorna 1 no guardó el elemento
            //     $response = new ApiResponse(REQUEST_ERROR_TYPE::DATABASE, 'Error al borrar la media.');
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

            $consulta = $objetoAccesoDato->RetornarConsulta('SELECT id FROM medias WHERE code = :code');
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

            $consulta = $objetoAccesoDato->RetornarConsulta('SELECT * FROM medias');
            $row = $consulta->execute();

            foreach ($consulta->fetchAll() as $row) {
                array_push($arrayMedias, $row);
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

            $consulta = $objetoAccesoDato->RetornarConsulta('SELECT * FROM medias where id = :id');
            $row = $consulta->execute(array(':id' => $id));

            foreach ($consulta->fetchAll() as $row) {
                array_push($arrayMedias, $row);
            }

        } catch (PDOException $exception) {
            throw $exception;
        } catch (Exception $exception) {
            throw $exception;
        }

        return $arrayMedias;
    }

}
