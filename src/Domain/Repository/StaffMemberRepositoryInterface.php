<?php

namespace App\Domain\Repository;

use App\Domain\Model\StaffMember;
use App\Domain\Model\User;
use PDO;

interface StaffMemberRepositoryInterface
{
    public function __construct(PDO $pdo);
    public function allMembers(): array;
    public function getMember(int $id): StaffMember;
    public function insert(StaffMember $staffMember): bool;
    public function fireStaffMember(StaffMember $staffMember): bool;
}
