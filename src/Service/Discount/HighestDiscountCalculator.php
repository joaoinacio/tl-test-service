<?php

namespace App\Service\Discount;

use App\ValueObject\Order;
use App\ValueObject\Discount;

/**
 * Composite discount calculator
 *
 * Given a list of inner calculators (rules), calculates and returns the highest discount.
 */
class HighestDiscountCalculator implements DiscountCalculatorInterface
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
        $discountToApply = new Discount(0, self::NO_DISCOUNT_REASON_TEXT);

        foreach ($this->calculators as $calculator) {
            if ($calculator->canApplyDiscount($order)) {
                $newDiscount = $calculator->calculateDiscount($order);

                if ($newDiscount->getValue() > $discountToApply->getValue()) {
                    $discountToApply = $newDiscount;
                }
            }
        }

        return $discountToApply;
    }
}
