<?php
namespace App\Tests\Entity\Repository;

use App\Entity\Product;
use App\Entity\Repository\ProductRepository;
use App\Service\Discount\Rule\BuyTwoOrMoreFromCategoryToolsTwentyPercentCheapest;
use App\ValueObject\Order;
use App\ValueObject\OrderItem;
use App\ValueObject\Discount;
use Prophecy\Prophet;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class BuyTwoOrMoreFromCategoryToolsTwentyPercentCheapestTest extends KernelTestCase
{

    protected function setUp()
    {
        self::bootKernel();
        $this->prophet = new Prophet();
    }

    private function createInstance()
    {
        $productRepository = $this->prophet->prophesize(ProductRepository::class);
        $productRepository->getById('A101')->willReturn(new Product('A101', 'Screwdriver', '1', '9.75'));
        $productRepository->getById('A102')->willReturn(new Product('A101', 'Electric screwdriver', '1', '49.50'));
        $productRepository->getById('B101')->willReturn(new Product('B101', 'Basic on-off switch', '2', '4.99'));
        $productRepository->getById('B102')->willReturn(new Product('B102', 'Press button', '2', '4.99'));
        $productRepository->getById('B103')->willReturn(new Product('B103', 'Switch with motion detector', '2', '12.95'));

        return new BuyTwoOrMoreFromCategoryToolsTwentyPercentCheapest($productRepository->reveal());
    }

    public function testCanApplyDiscountItemsNok()
    {
        $calculator = $this->createInstance();

        $orderItems = [
          new OrderItem('A101', '2', '9.75', '19.50')
        ];
        $order = new Order(1, 1, $orderItems, 19.90);

        $result = $calculator->canApplyDiscount($order);
        $this->assertEquals($result, false);
    }

    public function testCanApplyDiscountForItemsOk()
    {
      $calculator = $this->createInstance();

      $orderItems = [
        new OrderItem('B101', '2', '4.99', '19.50'),
        new OrderItem('B102', '1', '49.50', '49.50')
      ];
      $order = new Order(3, 3, $orderItems, 69.00);

      $result = $calculator->canApplyDiscount($order);
      $this->assertEquals($result, true);
    }

    public function testDiscountForItemsCategory2()
    {
        $calculator = $this->createInstance();

        $orderItems = [
          new OrderItem('B101', '2', '4.99', '19.50'),
          new OrderItem('B102', '1', '49.50', '49.50')
        ];
        $order = new Order(3, 3, $orderItems, 69.00);

        $discount = $calculator->calculateDiscount($order);
        $this->assertInstanceOf(Discount::class, $discount);
        $this->assertEquals(4.99 * 0.20, $discount->getValue());
    }

    public function testDiscountForItemsCategory2_OnlyCheapest()
    {
        $calculator = $this->createInstance();

        $orderItems = [
          new OrderItem('B101', '2', '4.99', '19.50'),
          new OrderItem('B103', '2', '12.95', '25.90')
        ];
        $order = new Order(3, 3, $orderItems, 45.40);

        $discount = $calculator->calculateDiscount($order);
        $this->assertInstanceOf(Discount::class, $discount);
        $this->assertEquals(4.99 * 0.20, $discount->getValue());
    }

}