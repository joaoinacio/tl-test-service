<?php

namespace App\Service\Discount\Rule;

use App\Entity\Repository\ProductRepository;
use App\Service\Discount\DiscountCalculatorInterface;
use App\ValueObject\Order;
use App\ValueObject\Discount;

/**
 * Discount calculator rule:
 * For every product of category "Switches" (id 2), when you buy five, you get a sixth for free
 */
class BuyFiveFromCategory2GetSixthFree implements DiscountCalculatorInterface
{
    const DISCOUNT_REASON_TEXT = 'For every product of category "Switches" (id 2), when you buy five, you get a sixth for free';
    const DISCOUNT_ITEM_CATEGORY = 2;
    const DISCOUNT_ITEMS_QUANTITY = 5;

    private $productRepository;

    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    /**
     * @inheritDoc
     */
    public function canApplyDiscount(Order $order): bool
    {
        foreach ($order->getItems() as $orderItem) {
            $product = $this->productRepository->getById($orderItem->getProductId());

            if ($product->getCategory() == self::DISCOUNT_ITEM_CATEGORY
                && $orderItem->getQuantity() >= self::DISCOUNT_ITEMS_QUANTITY) {
                return true;
            }
        }

        return false;
    }

    /**
     * @inheritDoc
     */
    public function calculateDiscount(Order $order): Discount
    {
        $discountValue = 0;

        foreach ($order->getItems() as $orderItem) {
            $product = $this->productRepository->getById($orderItem->getProductId());

            if ($product->getCategory() == self::DISCOUNT_ITEM_CATEGORY
                && $orderItem->getQuantity() >= self::DISCOUNT_ITEMS_QUANTITY) {
                // each 6th item should be free
                $timesToDiscount = (int)($orderItem->getQuantity() / (self::DISCOUNT_ITEMS_QUANTITY+1));
                $productsDiscount = $orderItem->getUnitPrice() * $timesToDiscount;

                $discountValue += $productsDiscount;
            }
        }


        return new Discount($discountValue, self::DISCOUNT_REASON_TEXT);
    }
}
