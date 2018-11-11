<?php

namespace App\Entity\Repository;

use App\Entity\Product;

class ProductRepository extends AbstractRepository
{
    /**
     * @return Product|null
     */
    public function getById($id): ?Product
    {
        $data = $this->gateway->fetchSingle('products', ['id' => $id]);

        if (empty($data)) {
            return null;
        }

        $product = $this->normalizer->denormalize($data, Product::class);
        if (!$product instanceof Product) {
            throw new \Exception('Error retrieving product data');
        }

        return $product;
    }
}
