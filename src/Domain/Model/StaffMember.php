<?php

declare(strict_types=1);

namespace App\Domain\Model;

use DateTimeImmutable;

class StaffMember
{
    public function __construct(private ?int $id, private string $name, private ?bool $isFired = false)
    {
    }

    public function setId(int $id): void
    {
        if (!is_null($this->id)) {
            throw new \DomainException('You can define the ID only once');
        }

        $this->id = $id;
    }

    public function id(): ?int
    {
        return $this->id;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function isFired(): bool
    {
        return $this->isFired;
    }

    public function fireStaffMember(): void
    {
        if ($this->isFired) {
            throw new \DomainException('You can fire the staff member only once');
        }

        $this->isFired = true;
    }
}
