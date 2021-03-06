<?php

namespace App\ValueObject;

class Discount
{
    public $value;

    public $reasonText;

    public function __construct($value, $reasonText)
    {
        $this->value = $value;
        $this->reasonText = $reasonText;
    }

    public function getValue(): float
    {
        return (float)$this->value;
    }
}
