<?php

use App\Domain\Model\Service\Service;
use App\Domain\Model\StaffMember;
use App\Infrastructure\Database\ConnectionCreator;
use App\Infrastructure\Repository\PdoServiceRepository;
use App\Infrastructure\Repository\PdoStaffMemberRepository;

require_once 'vendor/autoload.php';

$pdo = (new ConnectionCreator())->create();
$staffMemberRepository = new PdoStaffMemberRepository($pdo);
$serviceRepository = new PdoServiceRepository($pdo);

$member = new StaffMember(
    null,
    'Danton teste',
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

$serviceRepository->inactiveService($service);

//List all
var_dump($serviceRepository->allServices($staffMemberRepository));


