<?php

namespace App\Service\Discount\Rule;

use App\Entity\Customer;
use App\Entity\Repository\CustomerRepository;
use App\Service\Discount\DiscountCalculatorInterface;
use App\ValueObject\Order;
use App\ValueObject\Discount;

class TenPercentOnCustomerRevenue1000 implements DiscountCalculatorInterface
{
    const DISCOUNT_VALUE_PERCENT = 0.1;
    const DISCOUNT_REASON_TEXT = 'Customer has already bought for over € 1000, 10% discount on the whole order.';

    private $customerRepository;

    public function __construct(CustomerRepository $customerRepository)
    {
        $this->customerRepository = $customerRepository;
    }

    /**
     * @inheritDoc
     */
    public function canApplyDiscount(Order $order): bool
    {
        $customer = $this->customerRepository->getById($order->customerId);

        return $customer->getRevenue() >= 1000.0;
    }

    /**
     * @inheritDoc
     */
    public function calculateDiscount(Order $order): Discount
    {
        if (!$this->canApplyDiscount($order)) {
            throw new \Exception('Can not calculate discount for this order.');
        }

        $discountValue = $order->total * self::DISCOUNT_VALUE_PERCENT;

        return new Discount($discountValue, self::DISCOUNT_REASON_TEXT);
    }
}
