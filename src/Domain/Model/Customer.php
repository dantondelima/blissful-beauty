<?php

declare(strict_types=1);

namespace App\Domain\Model;

use DateTimeInterface;

class Customer
{
    public function __construct(
        private ?int $id,
        private string $name,
        private DateTimeInterface $birthDate,
        private string $document,
        private ?bool $isBanned = false
    ) {
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

    public function birthDate(): DateTimeInterface
    {
        return $this->birthDate;
    }

    public function document(): string
    {
        return $this->document();
    }

    public function isBanned(): bool
    {
        return $this->isBanned;
    }

    public function banCustomer(): void
    {
        if ($this->isBanned) {
            throw new \DomainException('You can ban the customer only once');
        }

        $this->isBanned = true;
    }
}
