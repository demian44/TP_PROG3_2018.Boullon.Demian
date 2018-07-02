<?php

class OrderItem extends Item
{
    private $orderId;
    private $itemId;
    private $cant;

    public function __construct(int $itemId, int $cant)
    {
        $this->SetItemId($itemId);
        $this->SetCant($cant);
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
