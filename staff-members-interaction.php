<?php

use App\Domain\Model\StaffMember;
use App\Infrastructure\Database\ConnectionCreator;
use App\Infrastructure\Repository\PdoStaffMemberRepository;

require_once 'vendor/autoload.php';

$pdo = (new ConnectionCreator())->create();
$memberRepository = new PdoStaffMemberRepository($pdo);

$member = new StaffMember(
    null,
    'Danton teste',
);

//insert
$memberRepository->insert($member);

var_dump($member);

$memberRepository->fireStaffMember($member);

//List all
var_dump($memberRepository->allMembers());
