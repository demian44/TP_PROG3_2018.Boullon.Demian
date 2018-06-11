<?php
class UserRepository
{

    /**
     * Agregar Throw
     */
    public function InsertUser($user)
    {

        $response = new InternalResponse();
        $response->SetMessege("Carga exitosa");
        try {
            $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
            $consulta = $objetoAccesoDato->RetornarConsulta("INSERT INTO users (name,category,user,password)"
            . "VALUES(:name,:category,:user,:password)");

            
            $consulta->bindValue(':name', $user->GetName(), PDO::PARAM_STR);
            $consulta->bindValue(':category', $user->GetCategory(), PDO::PARAM_INT);
            echo "\n\n\ncoso!!!\n\n\n";
            $consulta->bindValue(':user', $user->GetUser(), PDO::PARAM_STR);
            $consulta->bindValue(':password', $user->GetPass(), PDO::PARAM_STR);


            if (!$consulta->execute()) //Si no retorna 1 no guardó el elemento
            $response->SetMessege("Error al guardar al usuario en la base de datos.");
            echo "coso!!!";
        } catch (PDOException $exception) {
            echo "coso!!!";
            throw $exception;
        } catch (Exception $exception) {
            echo "coso!!!";
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