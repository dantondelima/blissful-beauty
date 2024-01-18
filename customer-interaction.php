<?php

use App\Domain\Model\Customer;
use App\Infrastructure\Database\ConnectionCreator;
use App\Infrastructure\Repository\PdoCustomerRepository;

require_once 'vendor/autoload.php';

$pdo = (new ConnectionCreator())->create();
$customerRepository = new PdoCustomerRepository($pdo);

$customer = new Customer(
    null,
    'Danton teste',
    new DateTimeImmutable('2000-12-12'),
    '572178527'
);

//insert
$customerRepository->insert($customer);

var_dump($customer);

$customerRepository->banCustomer($customer);

//List all
var_dump($customerRepository->allCustomers());
