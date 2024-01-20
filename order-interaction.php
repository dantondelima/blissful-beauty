<?php

use App\Domain\Model\Customer;
use App\Domain\Model\Order\Order;
use App\Domain\Model\StaffMember;
use App\Domain\Model\Service\Service;
use App\Domain\Model\Service\Schedule;
use App\Infrastructure\Database\ConnectionCreator;
use App\Infrastructure\Repository\PdoCustomerRepository;
use App\Infrastructure\Repository\Order\PdoOrderRepository;
use App\Infrastructure\Repository\PdoStaffMemberRepository;
use App\Infrastructure\Repository\Service\PdoServiceRepository;
use App\Infrastructure\Repository\Service\PdoScheduleRepository;

require_once 'vendor/autoload.php';

$pdo = (new ConnectionCreator())->create();
$staffMemberRepository = new PdoStaffMemberRepository($pdo);
$serviceRepository = new PdoServiceRepository($pdo);
$scheduleRepository = new PdoScheduleRepository($pdo);
$customerRepository = new PdoCustomerRepository($pdo);
$orderRepository = new PdoOrderRepository($pdo);


$pdo->beginTransaction();

try {
    $member = new StaffMember(
        null,
        'Danton staff',
    );

    $staffMemberRepository->insert($member);

    $service = new Service(
        null,
        'Manicure',
        5000,
        30,
        $member
    );

    //insert
    $serviceRepository->insert($service);

    $schedule = new Schedule(
        null, 
        new DateTimeImmutable('2024-01-20 12:00'),
        $service
    );

    $scheduleRepository->insert($schedule);

    $customer = new Customer(
        null, 
        'Danton cliente',
        new DateTimeImmutable('2000-12-12'),
        '572369213',
    );

    $customerRepository->insert($customer);

    $order = new Order(
        null, 
        $customer,
        $schedule
    );

    //List all
    var_dump($orderRepository->allOrders($customerRepository, $scheduleRepository, $serviceRepository, $staffMemberRepository));

    $orderRepository->insert($order);

    var_dump($orderRepository->allOrders($customerRepository, $scheduleRepository, $serviceRepository, $staffMemberRepository));
    $pdo->commit();
} catch (\PDOException $e) {
    echo $e->getMessage();
    $pdo->rollBack();
}