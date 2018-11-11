<?php
namespace App\Tests\Entity\Repository;

use App\Entity\Repository\CustomerRepository;
use App\Entity\Customer;
use App\Service\Gateway\JsonDataFile;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class CustomerRepositoryTest extends KernelTestCase
{
    protected function setUp()
    {
        self::bootKernel();
    }

    private function createRepository(): CustomerRepository
    {
        // TODO: mock the json file loader gateway and provide own data.
        $fileLoaderGateway = self::$container->get('app.data_gateway');
        $objectNormalizer = new ObjectNormalizer();

        return new CustomerRepository($fileLoaderGateway, $objectNormalizer);
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
        $repository = $this->createCustomerRepository();
        $customer = $repository->getById(123);

        $this->assertNull($customer);
    }
}

