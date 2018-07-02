<?php

class Item
{
    private $id;
    private $name;
    private $employeeType;

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

    public function GetEmployeeType()
    {
        return $this->employeeType;
    }
    public function SetEmployeeType(int $employeeType)
    {
        if ($employeeType >= 0) {
            $this->employeeType = $employeeType;
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
