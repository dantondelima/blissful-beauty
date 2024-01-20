<?php

namespace App\Domain\Repository\Order;

use PDO;
use App\Domain\Model\Order\Order;
use App\Domain\Repository\CustomerRepositoryInterface;
use App\Domain\Repository\StaffMemberRepositoryInterface;
use App\Domain\Repository\Service\ServiceRepositoryInterface;
use App\Domain\Repository\Service\ScheduleRepositoryInterface;

interface OrderRepositoryInterface
{
    public function __construct(PDO $pdo);
    public function allOrders(
        CustomerRepositoryInterface $pdoCustomerRepository,
        ScheduleRepositoryInterface $pdoScheduleRepository,
        ServiceRepositoryInterface $pdoServiceRepository, 
        StaffMemberRepositoryInterface $pdoStaffMemberRepository
    ): array;
    public function insert(Order $order): bool;
}
