<?php
namespace App\Tests\Entity\Repository;

use App\Entity\Customer;
use App\Entity\Repository\CustomerRepository;
use App\Service\Gateway\JsonDataFile;
use Prophecy\Prophet;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class CustomerRepositoryTest extends KernelTestCase
{
    protected function setUp()
    {
        self::bootKernel();
        $this->prophet = new Prophet();
    }

    private function createRepository(): CustomerRepository
    {
        $fileLoaderGateway = $this->prophet->prophesize(JsonDataFile::class);
        $fileLoaderGateway->fetchSingle('customers', ['id' => 1])
            ->willReturn([
                "id" => "1",
                "name" => "Coca Cola",
                "since" => "2014-06-28",
                "revenue" => "492.12"
            ]);

        $fileLoaderGateway->fetchSingle('customers',\Prophecy\Argument::any())
            ->willReturn([]);

        return new CustomerRepository(
            $fileLoaderGateway->reveal(),
            new ObjectNormalizer()
        );
    }

    public function testGetCustomerByIdValid()
    {
        $repository = $this->createRepository();
        $customer = $repository->getById(1);

        $this->assertInstanceOf(Customer::class, $customer);
        $this->assertEquals('Coca Cola', $customer->name);
    }

    public function testGetCustomerByIdDoesntExist()
    {
        $repository = $this->createRepository();
        $customer = $repository->getById(123);

        $this->assertNull($customer);
    }
}

