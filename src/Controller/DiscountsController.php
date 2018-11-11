<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Service\Discount\DiscountCalculatorInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class DiscountsController extends AbstractController
{
    /**
     * @var DiscountCalculatorInterface $discountCalculator
     */
    private $discountCalculator;
    private $normalizer;

    public function __construct(DiscountCalculatorInterface $discountCalculator, NormalizerInterface $normalizer)
    {
        $this->discountCalculator = $discountCalculator;
        $this->normalizer = $normalizer;
    }

    public function index()
    {
        // TODO: unserialize/denormalize input order
        $order = new \App\ValueObject\Order('1', 1, [], 500);

        // calculate discount
        $discount = $this->discountCalculator->calculateDiscount($order);

        return $this->json($this->normalizer->normalize($discount));
    }
}
