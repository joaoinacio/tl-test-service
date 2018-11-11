# Test microservice

A microservice that provides an API to perform discount calculations on submitted orders

## Requirements

In order to develop, test or run the application, a docker image has been added with php 7.1, composer and other dependencies
(see [docker](https://docs.docker.com/install/) and [docker-compose](https://docs.docker.com/compose/install/)).

## Running the application

 1) Initial installation of dependencies: `docker-compose run microservice composer install`

 2) Run tests: `docker-compose run microservice make tests` (runs CS checks, code analysis, unit tests)

 3) Running the built-in web server: `docker-compose up` (maps to [localhost:82](http://localhost:82))

