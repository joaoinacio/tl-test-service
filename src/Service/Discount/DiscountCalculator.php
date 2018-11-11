<?php

namespace App\Service\Discount;

use App\ValueObject\Order;
use App\ValueObject\Discount;

class DiscountCalculator implements DiscountCalculatorInterface
{
    const NO_DISCOUNT_REASON_TEXT = 'No discount.';

    public function __construct(iterable $rules = [])
    {
        foreach ($rules as $innerCalculator) {
            $this->addDiscountCalculator($innerCalculator);
        }
    }

    /**
     * @var DiscountCalculatorInterface[]
     */
    private $calculators = [];

    public function addDiscountCalculator(DiscountCalculatorInterface $calculator)
    {
        // TODO: also allow specifying a priority?
        $this->calculators[] = $calculator;
    }

    /**
     * @inheritDoc
     */
    public function canApplyDiscount(Order $order): bool
    {
        // assume there can always be discounts, may return empty array
        return true;
    }

    /**
     * @inheritDoc
     */
    public function calculateDiscount(Order $order): Discount
    {
        $discounts = [];
        foreach ($this->calculators as $calculator) {
            if ($calculator->canApplyDiscount($order)) {
                return $calculator->calculateDiscount($order);
            }
        }

        return new Discount(0, self::NO_DISCOUNT_REASON_TEXT);
    }
}
