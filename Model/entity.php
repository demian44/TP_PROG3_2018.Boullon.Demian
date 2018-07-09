<?php
/*
 * Los usuarios pueden ser socios o empleados
 */
class Entity
{
    private $id;
    private $active;

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

    public function GetActive()
    {
        return $this->active;
    }

    public function SetActive($active)
    {
        $this->active = $active;
    }
}
