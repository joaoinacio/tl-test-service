<?php
namespace App\Tests\Entity\Repository;

use App\Entity\Repository\ProductRepository;
use App\Entity\Product;
use App\Service\Gateway\JsonDataFile;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class ProductRepositoryTest extends KernelTestCase
{
    protected function setUp()
    {
        self::bootKernel();
    }

    private function createRepository(): ProductRepository
    {
        // TODO: mock the json file loader gateway and provide own data.
        $fileLoaderGateway = self::$container->get('app.data_gateway');
        $objectNormalizer = new ObjectNormalizer();

        return new ProductRepository($fileLoaderGateway, $objectNormalizer);
    }

    public function testGetCustomerByIdValid()
    {
        $repository = $this->createRepository();
        $product = $repository->getById('A102');

        $this->assertInstanceOf(Product::class, $product);
        $this->assertEquals('Electric screwdriver', $product->description);
    }

    public function testGetCustomerByIdDoesntExist()
    {
        $repository = $this->createRepository();
        $product = $repository->getById('A200');

        $this->assertNull($product);
    }
}

