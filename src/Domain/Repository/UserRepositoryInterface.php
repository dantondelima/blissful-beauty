<?php

namespace App\Domain\Repository;

use App\Domain\Model\User;
use PDO;

interface UserRepositoryInterface
{
    public function __construct(PDO $pdo);
    public function allUsers(): array;
    public function insert(User $user): bool;
    public function remove(User $user): bool;
}
