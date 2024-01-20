<?php

use App\Domain\Model\StaffMember;
use App\Domain\Model\Service\Service;
use App\Domain\Model\Service\Schedule;
use App\Infrastructure\Database\ConnectionCreator;
use App\Infrastructure\Repository\PdoStaffMemberRepository;
use App\Infrastructure\Repository\Service\PdoServiceRepository;
use App\Infrastructure\Repository\Service\PdoScheduleRepository;

require_once 'vendor/autoload.php';

$pdo = (new ConnectionCreator())->create();
$staffMemberRepository = new PdoStaffMemberRepository($pdo);
$serviceRepository = new PdoServiceRepository($pdo);
$scheduleRepository = new PdoScheduleRepository($pdo);

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

//List all
var_dump($scheduleRepository->allSchedules($serviceRepository, $staffMemberRepository));

$scheduleRepository->takeSchedule($schedule);

var_dump($scheduleRepository->allSchedules($serviceRepository, $staffMemberRepository));
