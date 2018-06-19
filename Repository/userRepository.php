<?php


class UserRepository
{
    /**
     * Agregar Throw.
     */
    public function InsertUser($user)
    {
        $result;
        try {
            $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
            $consulta = $objetoAccesoDato->RetornarConsulta('INSERT INTO users (name,category,user,password)'
            .'VALUES(:name,:category,:user,:password)');

            $consulta->bindValue(':name', $user->GetName(), PDO::PARAM_STR);
            $consulta->bindValue(':category', $user->GetCategory(), PDO::PARAM_INT);
            $consulta->bindValue(':user', $user->GetUser(), PDO::PARAM_STR);
            $consulta->bindValue(':password', $user->GetPass(), PDO::PARAM_STR);

            if (!$consulta->execute()) { //Si no retorna 1 no guardó el elemento
                $result = new ApiResponse(REQUEST_ERROR_TYPE::DATABASE, 'Error al guardar al usuario en la base de datos.');
            } else {
                $result = new ApiResponse(REQUEST_ERROR_TYPE::NOERROR, 'Usuario guardado exitosamente');
                //aspdajsdpsaod
            }
        } catch (PDOException $exception) {
            throw $exception;
        } catch (Exception $exception) {
            throw $exception;
        }

        return $result;
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
