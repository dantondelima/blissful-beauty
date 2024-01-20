<?php

declare(strict_types=1);

namespace App\Infrastructure\Repository\Service;

use PDO;
use PDOStatement;
use DateTimeImmutable;
use App\Domain\Model\Service\Schedule;
use App\Infrastructure\Repository\PdoStaffMemberRepository;
use App\Domain\Repository\Service\ScheduleRepositoryInterface;
use App\Infrastructure\Repository\Service\PdoServiceRepository;

class PdoScheduleRepository implements ScheduleRepositoryInterface
{
    public function __construct(private PDO $connection)
    {
    }

    public function allSchedules(PdoServiceRepository $pdoServiceRepository, PdoStaffMemberRepository $pdoStaffMemberRepository): array
    {
        $query = 'SELECT * FROM schedules;';
        $stmt = $this->connection->query($query);

        return $this->hydrateSchedulesList($stmt, $pdoServiceRepository, $pdoStaffMemberRepository);
    }

    public function insert(Schedule $schedule): bool
    {
        $serviceId = $schedule->service()->id();

        if (!$serviceId) {
            throw new \DomainException('You can\'t create a schedule without a service');
        }

        $sqlInsert = 'INSERT INTO schedules (date, is_taken, service_id) 
                        VALUES (:date, :is_taken, :service_id);';
        $stmt = $this->connection->prepare($sqlInsert);
        $stmt->bindValue(':date', $schedule->date()->format('Y-m-d H:i'));
        $stmt->bindValue(':is_taken', $schedule->isTaken());
        $stmt->bindValue(':service_id', $serviceId);
        $success = $stmt->execute();

        if ($success) {
            $schedule->setId((int) $this->connection->lastInsertId());
        }

        return $success;
    }

    public function takeSchedule(Schedule $schedule): bool
    {
        $sqlTakeSchedule = 'UPDATE schedules SET is_taken = :is_taken WHERE id = :id;';
        $stmt = $this->connection->prepare($sqlTakeSchedule);
        $stmt->bindValue(':is_taken', 1);
        $stmt->bindValue(':id', $schedule->id());
        $success = $stmt->execute();

        if ($success) {
            $schedule->takeSchedule();
        }

        return $success;
    }

    private function hydrateSchedulesList(
        PDOStatement $stmt, 
        PdoServiceRepository $pdoServiceRepository,
        PdoStaffMemberRepository $pdoStaffMemberRepository): array
    {
        $schedulesDataList = $stmt->fetchAll();
        $schedulesList = [];

        foreach ($schedulesDataList as $schedule) {
            $service = $pdoServiceRepository->getService($schedule['service_id'], $pdoStaffMemberRepository);

            $schedulesList[] = new Schedule(
                $schedule['id'],
                new DateTimeImmutable($schedule['date']),
                $service,
                (bool) $schedule['is_taken'],
            );
        }

        return $schedulesList;
    }
}
