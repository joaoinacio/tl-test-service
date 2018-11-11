<?php
namespace App\Entity\Repository;

use App\Entity\Customer;

class CustomerRepository extends AbstractRepository
{
    /**
     * @param string|int $id
     *
     * @return Customer|null
     */
    public function getById($id): ?Customer
    {
        $data = $this->gateway->fetchSingle('customers', ['id' => $id]);

        if (empty($data)) {
            return null;
        }

        $customer = $this->normalizer->denormalize($data, Customer::class);
        if (!$customer instanceof Customer) {
            throw new \Exception('Error retrieving customer data');
        }

        return $customer;
    }
}
