<?php
class TokenRepository
{
    public static function CheckUser($user)
    {
        $response = new ApiResponse(REQUEST_ERROR_TYPE::TOKEN, "User incorrecto");

        try {

            $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
            $consulta = $objetoAccesoDato->RetornarConsulta('SELECT password,name FROM users WHERE ' .
                ' user = :user AND active=1');
            $consulta->execute(array(":user" => $user->GetUser()));

            $array = $consulta->fetchall();
            if (count($array) > 0) {
                $row = $array[0];

                if (isset($row["password"]) && $row["password"] == $user->GetPass()) {
                    $perfil = explode("-", $row["name"]);
                    $perfil = $perfil[1];

                    $response = new ApiResponse(REQUEST_ERROR_TYPE::NOERROR, $perfil);
                } else {
                    $response = new ApiResponse(REQUEST_ERROR_TYPE::TOKEN, "Pass incorrecto");
                }

            }
        } catch (PDOException $exception) {
            throw $exception;
        } catch (Exception $exception) {
            throw $exception;
        }

        return $response;

    }

}
