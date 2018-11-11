<?php
namespace App\Tests\Entity\Repository;

use App\Entity\Product;
use App\Entity\Repository\ProductRepository;
use App\Service\Gateway\JsonDataFile;
use Prophecy\Prophet;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class ProductRepositoryTest extends KernelTestCase
{
    protected function setUp()
    {
        self::bootKernel();
        $this->prophet = new Prophet();
    }

    private function createRepository(): ProductRepository
    {
        $fileLoaderGateway = $this->prophet->prophesize(JsonDataFile::class);
        $fileLoaderGateway->fetchSingle('products', ['id' => 'A102'])
            ->willReturn([
                "id" => "A102",
                "description" => "Electric screwdriver",
                "category" => "1",
                "price" => "49.50"
            ]);

        $fileLoaderGateway->fetchSingle('products',\Prophecy\Argument::any())
            ->willReturn([]);

        return new ProductRepository(
            $fileLoaderGateway->reveal(),
            new ObjectNormalizer()
        );
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

