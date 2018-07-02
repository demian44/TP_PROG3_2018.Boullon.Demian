<?php

class UserRepository
{
    public static function Insert(User $user)
    {
        $result;
        try {
            
            $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
            $consulta = $objetoAccesoDato->RetornarConsulta('INSERT INTO users (name,user,password,category)'
            . 'VALUES(:name,:user,:password,:category)');
            
            $consulta->bindValue(':name', $user->GetName(), PDO::PARAM_STR);
            $consulta->bindValue(':user', $user->GetUser(), PDO::PARAM_STR);
            $consulta->bindValue(':password', $user->GetPass(), PDO::PARAM_STR);
            $consulta->bindValue(':category', $user->GetCategory(), PDO::PARAM_STR);
            
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

    public static function TraerUsuarios(&$arrayUsuarios)
    {
        // $return = false;
        // $arrayUsuarios = [];
        // try {
        //     $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();

        //     $consulta = $objetoAccesoDato->RetornarConsulta('SELECT name,user FROM users ' .
        //         ' WHERE active=1');
        //     $row = $consulta->execute();

        //     foreach ($consulta->fetchAll() as $row) {
        //         $user["name"] = $row["name"];
        //         $user["perfil"] = ;
        //         $user["user"] = $row["user"];
        //         $user["category"] = $row["category"];
        //         array_push($arrayUsuarios, $user);
        //         $return = true;
        //     }

        // } catch (PDOException $exception) {
        //     throw $exception;
        // } catch (Exception $exception) {
        //     throw $exception;
        // }

        // return $return;
    }

    public static function TraerUsuarioPorId($id)
    {
        try {
            $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();

            $consulta = $objetoAccesoDato->RetornarConsulta('SELECT * FROM users where id = :id ' .
                ' AND active = 1');

            $consulta->execute(array(':id' => $id));
            $row = $consulta->fetch();
            if ($row) {
                $user["id"] = $row["id"];
                $user["name"] = $row["name"];
                $user["user"] = $row["user"];
                $user["password"] = $row["password"];
                $user["category"] = $row["category"];
            } else {
                $user = false;
            }

        } catch (PDOException $exception) {
            throw $exception;
        } catch (Exception $exception) {
            throw $exception;
        }

        return $user;
    }
    public static function ExisteUser($user)
    {
        try {
            $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();

            $consulta = $objetoAccesoDato->RetornarConsulta('SELECT * FROM users where user = :user ' .
                ' AND active = 1');

            $consulta->execute(array(':user' => $user));
            $row = $consulta->fetch();
            if ($row) {
                $user = true;
            } else {
                $user = false;
            }

        } catch (PDOException $exception) {
            throw $exception;
        } catch (Exception $exception) {
            throw $exception;
        }

        return $user;
    }
    public static function EditUsuarios($user)
    {
        try {
            $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
            $consulta = $objetoAccesoDato->RetornarConsulta(
                ' UPDATE users SET password = :password  WHERE user = :user');

            $consulta->bindValue(':user', $user->GetUser(), PDO::PARAM_STR);
            $consulta->bindValue(':password', $user->GetPass(), PDO::PARAM_STR);

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
    public static function EliminarUsuario($id)
    {
        try {
            $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
            $consulta = $objetoAccesoDato->RetornarConsulta(
                ' UPDATE users SET active = 0  WHERE id = :id');

            $consulta->bindValue(':id', $id, PDO::PARAM_STR);

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

}
