<?php

namespace App\Service\Gateway;

interface DataGatewayInterface
{
    /**
     * Retrieve an entity document given type $entity and $conditions
     *
     * @param string $entity
     * @param array $conditions
     *
     * @return mixed
     */
    public function fetchSingle(string $entity, array $conditions);
}
