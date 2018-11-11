<?php

namespace App\ValueObject;

class Order
{
    public $id;

    public $customerId;

    public $items;

    public $total;

    public function __construct($id, $customerId, $items, $total)
    {
        $this->id = $id;
        $this->customerId = $customerId;
        $this->items = $items;
        $this->total = (float)$total;
    }

    public function getItems(): array
    {
        return $this->items;
    }
}
