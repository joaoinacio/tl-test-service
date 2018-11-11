<?php

namespace App\Entity;

class Product
{
    public $id;

    public $description;

    public $category;

    public $price;

    public function __construct($id)
    {
        $this->id = $id;
    }

    public function getCategory()
    {
        return $this->category;
    }
}
