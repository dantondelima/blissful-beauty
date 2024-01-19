<?php

declare(strict_types=1);

namespace App\Infrastructure\Repository;

use App\Domain\Model\Service\Service;
use App\Domain\Repository\ServiceRepositoryInterface;
use PDO;
use PDOStatement;

class PdoServiceRepository implements ServiceRepositoryInterface
{
    public function __construct(private PDO $connection)
    {
    }

    public function allServices(PdoStaffMemberRepository $pdoStaffMemberRepository): array
    {
        $query = 'SELECT * FROM services;';
        $stmt = $this->connection->query($query);

        return $this->hydrateServicesList($stmt, $pdoStaffMemberRepository);
    }

    public function insert(Service $service): bool
    {
        $staffMember = $service->staffMember()->id();

        if (!$staffMember) {
            throw new \DomainException('You can\'t create a service without a staff member');
        }

        $sqlInsert = 'INSERT INTO services (name, price_in_cents, duration_minutes, is_active, member_id) 
                        VALUES (:name, :price_in_cents, :duration_minutes, :is_active, :member_id);';
        $stmt = $this->connection->prepare($sqlInsert);
        $stmt->bindValue(':name', $service->name());
        $stmt->bindValue(':price_in_cents', $service->priceInCents());
        $stmt->bindValue(':duration_minutes', $service->durationMinutes());
        $stmt->bindValue(':is_active', $service->isActive());
        $stmt->bindValue(':member_id', $staffMember);
        $success = $stmt->execute();

        if ($success) {
            $service->setId((int) $this->connection->lastInsertId());
        }

        return $success;
    }

    public function inactiveService(Service $service): bool
    {
        $sqlInactiveService = 'UPDATE services SET is_active = :is_active WHERE id = :id;';
        $stmt = $this->connection->prepare($sqlInactiveService);
        $stmt->bindValue(':is_active', 0);
        $stmt->bindValue(':id', $service->id());
        $success = $stmt->execute();

        if ($success) {
            $service->inactiveService();
        }

        return $success;
    }

    public function activeService(Service $service): bool
    {
        $sqlInactiveService = 'UPDATE services SET is_active = :is_active WHERE id = :id;';
        $stmt = $this->connection->prepare($sqlInactiveService);
        $stmt->bindValue(':is_active', 1);
        $stmt->bindValue(':id', $service->id());
        $success = $stmt->execute();

        if ($success) {
            $service->activeService();
        }

        return $success;
    }

    private function hydrateServicesList(PDOStatement $stmt, PdoStaffMemberRepository $pdoStaffMemberRepository): array
    {
        $servicesDataList = $stmt->fetchAll();
        $servicesList = [];

        foreach ($servicesDataList as $service) {
            $staffMember = $pdoStaffMemberRepository->getMember($service['member_id']);

            $servicesList[] = new Service(
                $service['id'],
                $service['name'],
                $service['price_in_cents'],
                $service['duration_minutes'],
                $staffMember,
                (bool) $service['is_active'],
            );
        }

        return $servicesList;
    }
}
