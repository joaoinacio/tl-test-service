<?php

namespace App\Normalizer;

use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use App\ValueObject\Order;
use App\ValueObject\OrderItem;

class OrderDenormalizer implements DenormalizerInterface
{
    public function denormalize($data, $class, $format = null, array $context = [])
    {
        $orderItems = [];
        foreach ($data['items'] as $item) {
            // could also use nested denormalizer
            $orderItems[] = new OrderItem(
                $item['product-id'],
                $item['quantity'],
                $item['unit-price'],
                $item['total']
            );
        }

        $order = new Order($data['id'], $data['customer-id'], $orderItems, $data['total']);

        return $order;
    }

    public function supportsDenormalization($data, $type, $format = null)
    {
        return $type === Order::class;
    }
}
