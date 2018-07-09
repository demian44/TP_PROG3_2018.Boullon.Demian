<?php

class UserActionRepository
{
    public static function SaveLogin(int $userId): void
    {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $objetoAccesoDato->RetornarConsulta('INSERT into login (user_id)'
            . 'values(:user_id)');
        $consulta->bindValue(':user_id', $userId, PDO::PARAM_INT);
        $consulta->execute();
    }
    
    public static function SaveByUser(string $action, string $user): void
    {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $objetoAccesoDato->RetornarConsulta('INSERT into user_actions (operation,user_id)'
            . 'values(:operation,(SELECT id FROM users WHERE user = :user))');
        $consulta->bindValue(':operation', $action, PDO::PARAM_INT);
        $consulta->bindValue(':user', $user, PDO::PARAM_INT);
        $consulta->execute();
    }

}
