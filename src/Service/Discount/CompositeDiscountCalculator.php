<?php

namespace App\Service\Discount;

use App\ValueObject\Order;

class CompositeDiscountCalculator implements MultipleDiscountCalculatorInterface
{
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
    public function calculateDiscounts(Order $order): array
    {
        $discounts = [];
        foreach ($this->calculators as $calculator) {
            if ($calculator->canApplyDiscount($order)) {
                $discounts[] = $calculator->calculateDiscount($order);
            }
        }

        return $discounts;
    }
}
