<?php
class TokenRepository
{

    /**
     * Agregar Throw
     */
    public function CheckUser($user)
    {
        $response = null;

        try {

            $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
            $consulta = $objetoAccesoDato->RetornarConsulta("SELECT password,name FROM users WHERE user = :user");
            $consulta->execute(array(":user" => $user->GetUser()));
            $row = $consulta->fetch();

            if (isset($row["password"]) && $row["password"] == $user->GetPass()) {
                $perfil = explode("-", $row["name"]);
                $perfil = $perfil[1];
                $response = new ApiResponse(REQUEST_ERROR_TYPE::NOERROR, $perfil);
            } else {
                $response = new ApiResponse(REQUEST_ERROR_TYPE::TOKEN, "User o pass incorrecto");
            }

        } catch (PDOException $exception) {
            throw $exception;
        } catch (Exception $exception) {
            throw $exception;
        }

        return $response;

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
