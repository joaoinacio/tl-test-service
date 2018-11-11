<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Service\Discount\DiscountCalculatorInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use App\ValueObject\Order;

class DiscountsController extends AbstractController
{
    /**
     * @var DiscountCalculatorInterface $discountCalculator
     */
    private $discountCalculator;

    /**
     * @var SerializerInterface
     */
    private $serializer;

    public function __construct(DiscountCalculatorInterface $discountCalculator, SerializerInterface $serializer)
    {
        $this->discountCalculator = $discountCalculator;
        $this->serializer = $serializer;
    }

    public function index(Request $request)
    {
        if ($request->headers->get('Content-Type') !== 'application/json') {
            return new Response('Bad Request', Response::HTTP_BAD_REQUEST);
        }

        $order = $this->serializer->deserialize($request->getContent(), Order::class, 'json');
        if (!$order instanceof Order) {
            return new Response('Bad Request', Response::HTTP_BAD_REQUEST);
        }

        // calculate discount
        $discount = $this->discountCalculator->calculateDiscount($order);

        return new JsonResponse($this->serializer->normalize($discount));
    }
}
