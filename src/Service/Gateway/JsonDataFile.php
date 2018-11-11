<?php

namespace App\Service\Gateway;

use Symfony\Component\Config\FileLocator;

class JsonDataFile implements DataGatewayInterface
{
    private $dataPath;
    private $data;

    public function __construct(string $dataPath)
    {
        $this->dataPath = $dataPath;
    }

    /**
     * Load entity data from file with name '<entity>.json'
     *
     * @param string $entity
     */
    protected function loadEntityData(string $entity)
    {
        $fileLocator = new FileLocator($this->dataPath);
        $entityFile = $fileLocator->locate("${entity}.json", null, true);

        if (!is_string($entityFile)) {
            return;
        }

        $jsonData = file_get_contents($entityFile);
        if (!$jsonData) {
            throw new \Exception(sprintf('Error while reading from "%s"', $entityFile));
        }
        $this->data[$entity] = json_decode($jsonData, true);
    }

    /**
     * Verifies if given $item matches all conditions in $conditions
     *
     * @param array $item
     * @param array $conditions
     */
    protected function matchesConditions(array $item, array $conditions)
    {
        foreach ($conditions as $key => $value) {
            if (!isset($item[$key])) {
                return false;
            }

            // could also perform not strict comparison here, due to json string numerics
            if ($item[$key] !== $value && $item[$key] !== (string)$value) {
                return false;
            }
        }

        return true;
    }

    /**
     * @inheritdoc
     */
    public function fetchSingle(string $entity, array $conditions)
    {
        if (!isset($this->data[$entity])) {
            $this->loadEntityData($entity);
        }

        foreach ($this->data[$entity] as $item) {
            if ($this->matchesConditions($item, $conditions)) {
                return $item;
            }
        }

        return null;
    }
}
