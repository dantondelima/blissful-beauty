<?php

declare(strict_types=1);

namespace App\Infrastructure\Repository;

use App\Domain\Model\StaffMember;
use App\Domain\Repository\StaffMemberRepositoryInterface;
use PDO;
use PDOStatement;

class PdoStaffMemberRepository implements StaffMemberRepositoryInterface
{
    public function __construct(private PDO $connection)
    {
    }

    public function allMembers(): array
    {
        $query = 'SELECT * FROM staff_members;';
        $stmt = $this->connection->query($query);

        return $this->hydrateMembersList($stmt);
    }

    private function hydrateMembersList(PDOStatement $stmt): array
    {
        $membersDataList = $stmt->fetchAll();
        $membersList = [];

        foreach ($membersDataList as $member) {
            $membersList[] = new StaffMember(
                $member['id'],
                $member['name'],
                (bool) $member['is_fired']
            );
        }

        return $membersList;
    }

    public function insert(StaffMember $staffMember): bool
    {
        $sqlInsert = "INSERT INTO staff_members (name) VALUES (:name);";
        $stmt = $this->connection->prepare($sqlInsert);
        $stmt->bindValue(':name', $staffMember->name());
        $success = $stmt->execute();

        if ($success) {
            $staffMember->setId((int) $this->connection->lastInsertId());
        }

        return $success;
    }

    public function fireStaffMember(StaffMember $staffMember): bool
    {
        $sqlFireMember = "UPDATE staff_members SET is_fired = :isFired WHERE id = :id;";
        $stmt = $this->connection->prepare($sqlFireMember);
        $stmt->bindValue(':isFired', 1);
        $stmt->bindValue(':id', $staffMember->id());
        $success = $stmt->execute();

        if ($success) {
            $staffMember->fireStaffMember();
        }

        return $success;
    }
}
