<?php
/*
 * Los usuarios pueden ser socios o empleados
 */
class Entity
{
    private $id;

    public function GetId()
    {
        return $this->id;
    }

    public function SetId($id)
    {
        $retorno = false;
        if (is_int($id) && $id >= 0) {
            $this->id = $id;
            $retorno = true;
        }

        return $retorno;
    }
}
