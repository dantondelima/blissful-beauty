<?php

namespace App\Domain\Repository\Service;

use PDO;
use App\Domain\Model\Service\Schedule;
use App\Infrastructure\Repository\PdoStaffMemberRepository;
use App\Infrastructure\Repository\Service\PdoServiceRepository;

interface ScheduleRepositoryInterface
{
    public function __construct(PDO $pdo);
    public function allSchedules(PdoServiceRepository $pdoServiceRepository, PdoStaffMemberRepository $pdoStaffMemberRepository): array;
    public function insert(Schedule $schedule): bool;
    public function takeSchedule(Schedule $schedule): bool;
}
