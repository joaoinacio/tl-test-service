<?php
namespace App\Entity\Repository;

use App\Service\Gateway\DataGatewayInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

abstract class AbstractRepository
{
    /**
     * @var DataGatewayInterface
     */
    protected $gateway;

    /**
     * @var DenormalizerInterface
     */
    protected $normalizer;

    public function __construct(DataGatewayInterface $gateway, DenormalizerInterface $normalizer)
    {
        $this->gateway = $gateway;
        $this->normalizer = $normalizer;
    }

    /**
     * @param string|int $id
     */
    abstract public function getById($id);
}
