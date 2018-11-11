<?php
namespace App\Tests\Entity\Repository;

use App\Entity\Customer;
use App\Entity\Repository\CustomerRepository;
use App\Service\Discount\Rule\TenPercentOnCustomerRevenue1000;
use App\ValueObject\Order;
use App\ValueObject\Discount;
use Prophecy\Prophet;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class TenPercentOnCustomerRevenue1000Test extends KernelTestCase
{

    protected function setUp()
    {
        self::bootKernel();
        $this->prophet = new Prophet();
    }

    private function createInstance()
    {
        $customerRepository = $this->prophet->prophesize(CustomerRepository::class);
        $customerRepository->getById(1)->willReturn(new Customer(1, 'Coca Cola', '2014-06-28', '492.12'));
        $customerRepository->getById(2)->willReturn(new Customer(2, 'TeamLeader', '2015-01-15', '1505.95'));

        return new TenPercentOnCustomerRevenue1000($customerRepository->reveal());
    }

    public function testCanApplyDiscountForOrderCustomerNok()
    {
        $calculator = $this->createInstance();
        $order = new Order(1, 1, [], 123);

        $result = $calculator->canApplyDiscount($order);
        $this->assertEquals($result, false);
    }

    public function testCanApplyDiscountForOrderCustomerOk()
    {
        $calculator = $this->createInstance();

        $order = new Order(1, 2, [], 123);

        $result = $calculator->canApplyDiscount($order);
        $this->assertEquals($result, true);
    }

    public function testDiscountForOrderCustomerNOk()
    {
        $calculator = $this->createInstance();
        $order = new Order(1, 1, [], 123);

        try {
            $result = $calculator->calculateDiscount($order);
            $this->fail('Expected exception not thrown');
        } catch (\Exception $e) {
            $this->assertInstanceOf(\Exception::class, $e);
        }
    }

    public function testDiscountForOrderCustomerOk()
    {
        $calculator = $this->createInstance();

        $order = new Order(1, 2, [], 123);

        $discount = $calculator->calculateDiscount($order);
        $this->assertInstanceOf(Discount::class, $discount);
        $this->assertEquals(12.3, $discount->getValue());
    }
}