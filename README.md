# Test microservice

A microservice that provides an API to perform discount calculations on submitted orders

## Requirements

In order to develop, test or run the application, a docker image has been added with php 7.1, composer and other dependencies
(see [docker](https://docs.docker.com/install/) and [docker-compose](https://docs.docker.com/compose/install/)).

## Running the application

 1) Initial installation of dependencies: `docker-compose run microservice composer install`

 2) Run tests: `docker-compose run microservice make tests` (runs CS checks, code analysis, unit tests)

 3) Running the built-in web server: `docker-compose up` (maps to [localhost:82](http://localhost:82))


## Usage

  The discount calculator is available at the root path of the microservice, so usage is simple:
  Just issue a POST request to the running web server port with order data json. Example using curl:

  ```
  curl -X POST \
    http://localhost:82/ \
    -H 'content-type: application/json' \
    -d '{
      "id": "2",
      "customer-id": "2",
      "items": [
        {
          "product-id": "B102",
          "quantity": "5",
          "unit-price": "4.99",
          "total": "24.95"
        }
      ],
      "total": "24.95"
    }'
  ```

## Development notes

### How does the discount calculation work?
There is an `app.discount_calculator` service which takes an order and returns a `Discount`.

The current implementation, `HighestDiscountCalculator`, returns the biggest discount from all the possible ones. However, it's possible to replace the service with an alternate implementation by modifying the `app.discount_calculator` alias in `config/services.yaml`

### How can I implement new discount calculation rules?

Discount calculation rules are automatically loaded into the calculator service above from [src/Service/Discount/Rule](src/Service/Discount/Rule).

So, just create a new class that implements the `DiscountCalculatorInterface` and add your own logic:

  ```php
  interface DiscountCalculatorInterface
  {
      /**
       * @param Order $order
       *
       * @return Boolean
       */
      public function canApplyDiscount(Order $order): bool;

      /**
       * @param Order $order
       *
       * @return Discount
       */
      public function calculateDiscount(Order $order): Discount;
  }
  ```

### How about fetching Customers/Products from an API?

Currently, there is only the possibility to read product/discount data from JSON files.

However, it's possible to add any kind of data access by creating a new class implementing the `DataGatewayInterface`:
  ```php
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
  ```

  Once that is done, just update the `app.data_gateway` service alias definition.