<?php

namespace App\Entity;

class Customer
{
    public $id;

    public $name;

    public $since;

    public $revenue;

    public function __construct($id, $name, $since, $revenue)
    {
        $this->id = $id;
        $this->name = $name;
        $this->since = $since;
        $this->revenue = $revenue;
    }

    public function getRevenue(): float
    {
        return (float)$this->revenue;
    }
}
