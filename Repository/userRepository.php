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

    public static function GetAllWithInfo()
    {
        $return = false;
        $arrayUsuarios = [];
        try {
            $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();

            $consulta = $objetoAccesoDato->RetornarConsulta('SELECT
            count(user_actions.operation) as cant_operaciones,MAX(login.date) as last_login,
            users.active,users.user,users.category
            FROM users
            LEFT JOIN user_actions
            ON users.id = user_actions.user_id
            LEFT JOIN login
            ON login.user_id = users.id
            WHERE users.active = 1
            GROUP BY users.id');

            $consulta->execute();
            $array = [];
            foreach ($consulta->fetchAll() as $row) {
                $userInfo = new UserInfo("", $row["user"], "", USER_CATEGORY::String($row["category"]));
                $userInfo->SetCantAperaciones($row["cant_operaciones"]);
                $userInfo->SetActive(User::ActiveToString($row["active"]));
                $userInfo->SetLogin($row["last_login"]);
                array_push($array, $userInfo->GetGeneralInfo());
            }

            $result = new ApiResponse(REQUEST_ERROR_TYPE::NOERROR, $array);

        } catch (PDOException $exception) {
            throw $exception;
        } catch (Exception $exception) {
            throw $exception;
        }

        return $result;
    }

    public static function GetSectorOperation()
    {
        $return = false;
        $arrayUsuarios = [];
        try {
            $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();

            $consulta = $objetoAccesoDato->RetornarConsulta('SELECT count(user_actions.id) total,
            users.category FROM user_actions
            INNER JOIN users ON users.id = user_actions.user_id
            GROUP BY users.category');
            $consulta->execute();
            $rows = $consulta->fetchall();
            $resultados = [];
            if ($rows) {
                foreach ($rows as $row) {
                    $respuesta["total"] = $row["total"];
                    $respuesta["sector"] = USER_CATEGORY::String($row["category"]);
                    array_push($resultados, $respuesta);
                }
                $result = new ApiResponse(REQUEST_ERROR_TYPE::NOERROR, $resultados);
            } else {
                $result = new ApiResponse(REQUEST_ERROR_TYPE::NOERROR, "Error en la consulta revise los datos ingresados.");
            }
        } catch (PDOException $exception) {
            throw $exception;
        } catch (Exception $exception) {
            throw $exception;
        }

        return $result;
    }

    public static function GetBySectorOperation($sector)
    {
        $return = false;
        echo $sector;
        $arrayUsuarios = [];
        try {
            $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();

            $consulta = $objetoAccesoDato->RetornarConsulta('SELECT count(user_actions.id) total,
            users.user, users.id as users_id FROM user_actions
            INNER JOIN users ON users.id = user_actions.user_id
            WHERE users.category = :sector
            GROUP BY users.id
            ');
            $consulta->bindValue(':sector', $sector, PDO::PARAM_INT);

            $consulta->execute();
            $rows = $consulta->fetchall();
            $resultados = [];
            if ($rows) {
                foreach ($rows as $row) {
                    $respuesta["total"] = $row["total"];
                    $respuesta["user"] = $row["user"];
                    $respuesta["user_id"] = $row["users_id"];
                    array_push($resultados, $respuesta);
                }
                $result = new ApiResponse(REQUEST_ERROR_TYPE::NOERROR, $resultados);
            } else {
                $result = new ApiResponse(REQUEST_ERROR_TYPE::NOERROR, "Error en la consulta revise los datos ingresados.");
            }
        } catch (PDOException $exception) {
            throw $exception;
        } catch (Exception $exception) {
            throw $exception;
        }

        return $result;
    }

    public static function DayAndHourEntry()
    {
        $return = false;
        $arrayUsuarios = [];
        try {
            $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();

            $consulta = $objetoAccesoDato->RetornarConsulta('SELECT login.date as ingreso,
            users.user,users.category
             FROM users
             LEFT JOIN login
             ON login.user_id = users.id
             WHERE users.active = 1');

            $consulta->execute();
            $array = [];
            foreach ($consulta->fetchAll() as $row) {
                $ingreso["ingreso"] = $row["ingreso"];
                $ingreso["user"] = $row["user"];
                $ingreso["category"] = USER_CATEGORY::String($row["category"]);
                array_push($array, $ingreso);
            }

            $result = new ApiResponse(REQUEST_ERROR_TYPE::NOERROR, $array);

        } catch (PDOException $exception) {
            throw $exception;
        } catch (Exception $exception) {
            throw $exception;
        }

        return $result;
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

    public static function GetByUser(string $user): User
    {
        try {
            $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();

            $consulta = $objetoAccesoDato->RetornarConsulta('SELECT * FROM users where user = :user ' .
                ' AND active = 1');

            $consulta->execute(array(':user' => $user));
            $row = $consulta->fetch();
            if ($row) {
                $user = new User($row["name"], $row["user"], $row["password"], $row["category"]);
                $user->SetId($row["id"]);
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
