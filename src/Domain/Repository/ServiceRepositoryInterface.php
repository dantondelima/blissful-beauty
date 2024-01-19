<?php

namespace App\Domain\Repository;

use App\Domain\Model\Service\Service;
use App\Infrastructure\Repository\PdoStaffMemberRepository;
use PDO;

interface ServiceRepositoryInterface
{
    public function __construct(PDO $pdo);
    public function allServices(PdoStaffMemberRepository $pdoStaffMemberRepository): array;
    public function insert(Service $service): bool;
    public function inactiveService(Service $service): bool;
    public function activeService(Service $service): bool;
}
