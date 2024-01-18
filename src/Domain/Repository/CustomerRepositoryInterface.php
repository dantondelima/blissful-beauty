<?php

namespace App\Domain\Repository;

use App\Domain\Model\Customer;
use PDO;

interface CustomerRepositoryInterface
{
    public function __construct(PDO $pdo);
    public function allCustomers(): array;
    public function insert(Customer $customer): bool;
    public function banCustomer(Customer $customer): bool;
}
