<?php

declare(strict_types=1);

namespace App\Infrastructure\Repository\Order;

use PDO;
use PDOStatement;
use App\Domain\Model\Order\Order;
use App\Domain\Repository\CustomerRepositoryInterface;
use App\Domain\Repository\Order\OrderRepositoryInterface;
use App\Domain\Repository\StaffMemberRepositoryInterface;
use App\Domain\Repository\Service\ServiceRepositoryInterface;
use App\Domain\Repository\Service\ScheduleRepositoryInterface;

class PdoOrderRepository implements OrderRepositoryInterface
{
    public function __construct(private PDO $connection)
    {
    }

    public function allOrders(
        CustomerRepositoryInterface $customerRepository, 
        ScheduleRepositoryInterface $scheduleRepository, 
        ServiceRepositoryInterface $serviceRepository, 
        StaffMemberRepositoryInterface $staffMemberRepository
    ): array
    {
        $query = 'SELECT * FROM orders;';
        $stmt = $this->connection->query($query);

        return $this->hydrateOrdersList($stmt, $customerRepository, $scheduleRepository, $serviceRepository, $staffMemberRepository);
    }

    public function insert(Order $order): bool
    {
        $customerId = $order->customer()->id();
        $scheduleId = $order->schedule()->id();

        if (!$customerId || !$scheduleId) {
            throw new \DomainException('You can\'t create a order without a customer or without a schedule');
        }

        $sqlInsert = 'INSERT INTO orders (customer_id, schedule_id) 
                        VALUES (:customer_id, :schedule_id);';
        $stmt = $this->connection->prepare($sqlInsert);
        $stmt->bindValue(':customer_id', $customerId);
        $stmt->bindValue(':schedule_id', $scheduleId);
        $success = $stmt->execute();

        if ($success) {
            $order->setId((int) $this->connection->lastInsertId());
        }

        return $success;
    }

    private function hydrateOrdersList(
        PDOStatement $stmt, 
        CustomerRepositoryInterface $customerRepository,
        ScheduleRepositoryInterface $scheduleRepository,
        ServiceRepositoryInterface $serviceRepository,
        StaffMemberRepositoryInterface $staffMemberRepository
    ): array
    {
        $ordersDataList = $stmt->fetchAll();
        $ordersList = [];

        foreach ($ordersDataList as $order) {
            $customer = $customerRepository->getCustomer($order['customer_id']);
            $schedule = $scheduleRepository->getSchedule($order['schedule_id'], $serviceRepository, $staffMemberRepository);

            $ordersList[] = new Order(
                $order['id'],
                $customer,
                $schedule
            );
        }

        return $ordersList;
    }
}
