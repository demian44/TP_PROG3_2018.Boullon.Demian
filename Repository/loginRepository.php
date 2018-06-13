<?php
class LoginRepository
{

    /**
     * Agregar Throw
     */
    public function CheckUser($user)
    {
        $response = new InternalResponse();
        $response->SetMessege("Login exitoso");
        try {
            $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
             
            $consulta = $objetoAccesoDato->RetornarConsulta("SELECT password,category FROM users WHERE user = :user");
            $consulta->execute(array(":user" => $user->GetUser()));
            $row = $consulta->fetch();
            
            if (isset($row["password"]) && $row["password"] == $user->GetPass()) {
                $response->SetElement(array("succesLogin" => true,"category"=>$row["category"]));
            }
            else{
                $response->SetMessege("User o pass incorrecto");
                $response->SetElement(array("succesLogin" => false));
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

?>