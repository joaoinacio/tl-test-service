<?php

namespace App\Entity;

class Product
{
    public $id;

    public $description;

    public $category;

    public $price;

    public function __construct($id, $description, $category, $price)
    {
        $this->id = $id;
        $this->description = $description;
        $this->category = $category;
        $this->price = (float)$price;
    }

    public function getCategory()
    {
        return $this->category;
    }

    public function getPrice(): float
    {
        return $this->price;
    }
}
