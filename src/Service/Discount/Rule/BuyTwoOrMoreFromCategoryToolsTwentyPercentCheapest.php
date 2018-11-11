<?php

namespace App\Service\Discount\Rule;

use App\Entity\Repository\ProductRepository;
use App\Service\Discount\DiscountCalculatorInterface;
use App\ValueObject\Order;
use App\ValueObject\Discount;

/**
 * Discount calculator rule:
 * "If you buy two or more products of category "Tools" (id 1), you get a 20% discount on the cheapest product."
 */
class BuyTwoOrMoreFromCategoryToolsTwentyPercentCheapest implements DiscountCalculatorInterface
{
    const DISCOUNT_REASON_TEXT = 'Buy two or more products of category "Tools" (id 1), get a 20% discount on the cheapest product.';
    const DISCOUNT_ITEM_CATEGORY = 2;
    const DISCOUNT_ITEMS_QUANTITY = 2;
    const DISCOUNT_PERCENT = 0.20;

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
        $productToDiscount = null;

        foreach ($order->getItems() as $orderItem) {
            $product = $this->productRepository->getById($orderItem->getProductId());

            if ($product->getCategory() == self::DISCOUNT_ITEM_CATEGORY
                && $orderItem->getQuantity() >= self::DISCOUNT_ITEMS_QUANTITY) {
                // discount possible, but applies only to the cheapest product
                if ($productToDiscount === null || $product->getPrice() < $productToDiscount->getPrice()) {
                    $productToDiscount = $product;
                    $discountValue = $product->getPrice() * self::DISCOUNT_PERCENT;
                }
            }
        }

        return new Discount($discountValue, self::DISCOUNT_REASON_TEXT);
    }
}
