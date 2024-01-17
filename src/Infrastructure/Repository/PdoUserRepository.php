<?php
declare(strict_types=1);

namespace App\Infrastructure\Repository;

use App\Domain\Model\User;
use App\Domain\Repository\UserRepositoryInterface;
use PDO;
use PDOStatement;

class PdoUserRepository implements UserRepositoryInterface
{
    public function __construct(private PDO $connection)
    {
    }

    public function allUsers(): array
    {
        $query = 'SELECT * FROM users;';
        $stmt = $this->connection->query($query);

        return $this->hydrateUsersList($stmt);
    }

    private function hydrateUsersList(PDOStatement $stmt): array
    {
        $usersDataList = $stmt->fetchAll();
        $userList = [];

        foreach ($usersDataList as $user) {
            $userList[] = new User(
                $user['id'],
                $user['name']
            );
        }
        
        return $userList;
    }

    public function insert(User $user): bool
    {
        $name = $user->name();

        $sqlInsert = "INSERT INTO users (name) VALUES (:name);";
        $stmt = $this->connection->prepare($sqlInsert);
        $stmt->bindParam(':name', $name);
        $success = $stmt->execute();

        if ($success) {
           $user->setId($this->connection->lastInsertId()); 
        }

        return $success;
    }

    public function remove(User $user): bool
    {
        $userId = $user->id();

        $sqlDelete = "DELETE FROM users WHERE id = :id";
        $stmt = $this->connection->prepare($sqlDelete);
        $stmt->bindParam(':id', $userId, PDO::PARAM_INT);
        
        return $stmt->execute();
    }
}
