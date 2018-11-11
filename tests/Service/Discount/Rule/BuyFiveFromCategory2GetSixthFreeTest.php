<?php
namespace App\Tests\Entity\Repository;

use App\Entity\Product;
use App\Entity\Repository\ProductRepository;
use App\Service\Discount\Rule\BuyFiveFromCategory2GetSixthFree;
use App\ValueObject\Order;
use App\ValueObject\OrderItem;
use App\ValueObject\Discount;
use Prophecy\Prophet;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class BuyFiveFromCategory2GetSixthFreeTest extends KernelTestCase
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

        return new BuyFiveFromCategory2GetSixthFree($productRepository->reveal());
    }

    public function testCanApplyDiscountItemsNok()
    {
        $calculator = $this->createInstance();

        $orderItems = [
          new OrderItem('B102', '4', '4.99', '49.90')
        ];
        $order = new Order(1, 1, $orderItems, 49.90);

        $result = $calculator->canApplyDiscount($order);
        $this->assertEquals($result, false);
    }

    public function testCanApplyDiscountForItemsOk()
    {
      $calculator = $this->createInstance();

      $orderItems = [
        new OrderItem('B102', '10', '4.99', '49.90')
      ];
      $order = new Order(1, 1, $orderItems, 49.90);

      $result = $calculator->canApplyDiscount($order);
      $this->assertEquals($result, true);
    }

    public function testDiscountFor10Items()
    {
      $calculator = $this->createInstance();

      $orderItems = [
        new OrderItem('B102', '10', '4.99', '49.90')
      ];
      $order = new Order(1, 1, $orderItems, 49.90);

      $discount = $calculator->calculateDiscount($order);
      $this->assertInstanceOf(Discount::class, $discount);
      $this->assertEquals(4.99, $discount->getValue());
    }

}