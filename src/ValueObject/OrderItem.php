<?php

namespace App\ValueObject;

class OrderItem
{
    public $productId;

    public $quantity;

    public $unitPrice;

    public $total;

    public function __construct($productId, $quantity, $unitPrice, $total)
    {
        $this->productId = (string)$productId;
        $this->quantity = (int)$quantity;
        $this->unitPrice = (float)$unitPrice;
        $this->total = (float)$total;
    }

    public function getProductId(): string
    {
        return $this->productId;
    }

    public function getQuantity(): float
    {
        return $this->quantity;
    }

    public function getUnitPrice(): float
    {
        return $this->unitPrice;
    }

    public function getTotal(): float
    {
        return $this->total;
    }
}
