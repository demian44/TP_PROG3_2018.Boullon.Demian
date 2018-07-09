<?php

class Item
{
    private $id;
    private $name;
    private $employeeType;
    private $precio;

    public function GetId()
    {
        return $this->id;
    }
    public function SetId(int $id)
    {
        if ($id >= 0) {
            $this->id = $id;
        }

    }

    public function GetItemId()
    {
        return $this->itemId;
    }
    public function SetItemId(int $itemId)
    {
        if ($itemId >= 0) {
            $this->itemId = $itemId;
        }
    }

    public function GetSector()
    {
        return $this->employeeType;
    }
    public function SetSector(int $employeeType)
    {
        if ($employeeType >= 0) {
            $this->employeeType = $employeeType;
        }
    }
   
    public function GetPrecio()
    {
        return $this->precio;
    }
    public function SetPrecio(int $precio)
    {
        if ($precio >= 0) {
            $this->precio = $precio;
        }
    }

    public function GetName()
    {
        return $this->name;
    }
    public function SetName($name)
    {
        $this->name = $name;
    }
 
}
