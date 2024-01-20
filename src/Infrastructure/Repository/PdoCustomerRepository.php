<?php

declare(strict_types=1);

namespace App\Infrastructure\Repository;

use App\Domain\Model\Customer;
use App\Domain\Repository\CustomerRepositoryInterface;
use DateTimeImmutable;
use PDO;
use PDOStatement;

class PdoCustomerRepository implements CustomerRepositoryInterface
{
    public function __construct(private PDO $connection)
    {
    }

    public function allCustomers(): array
    {
        $query = 'SELECT * FROM customers;';
        $stmt = $this->connection->query($query);

        return $this->hydrateCustomersList($stmt);
    }

    public function getCustomer(int $id): Customer
    {
        $query = 'SELECT * FROM customers WHERE id = :id;';
        $stmt = $this->connection->prepare($query);
        $stmt->bindValue(':id', $id);
        $stmt->execute();

        return $this->hydrateCustomersList($stmt)[0];
    }
    

    private function hydrateCustomersList(PDOStatement $stmt): array
    {
        $customersDataList = $stmt->fetchAll();
        $customersList = [];

        foreach ($customersDataList as $customer) {
            $customersList[] = new Customer(
                $customer['id'],
                $customer['name'],
                new DateTimeImmutable($customer['birth_date']),
                $customer['document'],
                (bool) $customer['is_banned']
            );
        }

        return $customersList;
    }

    public function insert(Customer $customer): bool
    {
        $sqlInsert = "INSERT INTO customers (name, birth_date, document) VALUES (:name, :birth_date, :document);";
        $stmt = $this->connection->prepare($sqlInsert);
        $stmt->bindValue(':name', $customer->name());
        $stmt->bindValue(':birth_date', $customer->birthDate()->format('Y-m-d'));
        $stmt->bindValue(':document', $customer->document());
        $success = $stmt->execute();

        if ($success) {
            $customer->setId((int) $this->connection->lastInsertId());
        }

        return $success;
    }

    public function banCustomer(Customer $customer): bool
    {
        $sqlBanCustomer = "UPDATE customers SET is_banned = :is_banned WHERE id = :id;";
        $stmt = $this->connection->prepare($sqlBanCustomer);
        $stmt->bindValue(':is_banned', 1);
        $stmt->bindValue(':id', $customer->id());
        $success = $stmt->execute();

        if ($success) {
            $customer->banCustomer();
        }

        return $success;
    }
}
