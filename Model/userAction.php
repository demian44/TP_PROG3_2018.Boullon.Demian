<?php
/*
 * Los usuarios pueden ser socios o empleados
 */
class UserAction extends Entity
{
    private $operation;
    private $date;
    private $userId;
    private $user;

    public function __construct($operation)
    {
        $this->operation = $operation;
    }

    /// Getters
    public function GetOperation()
    {
        return $this->operation;
    }

    public function GetUser()
    {
        return $this->user;
    }

    public function GetDate()
    {
        return $this->date;
    }

    public function GetUserId()
    {
        return $this->userId;
    }

    // End Getters

    ///Setters
    public function SetOperation($operation)
    {
        $retorno = false;
        if (is_string($operation) && $operation != '') {
            $this->operation = $operation;
            $retorno = true;
        }

        return $retorno;
    }

    public function SetUserId($userId)
    {
        $retorno = false;
        if (is_int($userId) && $userId >= 0) {
            $this->userId = $userId;
            $retorno = true;
        }
        return $retorno;
    }
    
    public function SetUser($user)
    {
        $retorno = false;
        if (is_int($user) && $user >= 0) {
            $this->user = $user;
            $retorno = true;
        }
        return $retorno;
    }

    public function SetDate($date)
    {
        $this->date = $date;
    }
 }
