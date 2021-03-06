<?php

namespace App\Service\Discount;

use App\Entity\Customer;
use App\ValueObject\Order;
use App\ValueObject\Discount;

interface DiscountCalculatorInterface
{
    /**
     * @param Order $order
     *
     * @return Boolean
     */
    public function canApplyDiscount(Order $order): bool;

    /**
     * @param Order $order
     *
     * @return Discount
     */
    public function calculateDiscount(Order $order): Discount;
}
