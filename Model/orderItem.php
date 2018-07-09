<?php

class OrderItem extends Item
{
    private $orderId;
    private $itemId;
    private $cant;
    private $stimatedTime;

    public function __construct(int $itemId)
    {
        $this->SetItemId($itemId);
        $this->orderId = -1;
    }

    public function GetOrderId()
    {
        return $this->orderId;
    }
    public function SetOrderId(int $orderId)
    {
        if ($orderId >= 0) {
            $this->orderId = $orderId;
        }

    }

    public function GetItemId()
    {
        return $this->itemId;
    }
    public function GetStimatedTime()
    {
        return $this->stimatedTime;
    }
   
    public function SetStimatedTime(int $stimatedTime)
    {
        if ($stimatedTime >= 0) {
            $this->stimatedTime = $stimatedTime;
        }
    }
   
    public function SetItemId(int $itemId)
    {
        if ($itemId >= 0) {
            $this->itemId = $itemId;
        }
    }

    public function GetCant()
    {
        return $this->cant;
    }
    public function SetCant(int $cant)
    {
        if ($cant >= 0) {
            $this->cant = $cant;
        }
    }
}
