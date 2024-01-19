<?php

declare(strict_types=1);

namespace App\Domain\Model\Service;

use App\Domain\Model\StaffMember;

class Service
{
    public function __construct(
        private ?int $id,
        private string $name,
        private int $priceInCents,
        private int $durationMinutes,
        private StaffMember $staffMember,
        private ?bool $isActive = true
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

    public function priceInCents(): int
    {
        return $this->priceInCents;
    }

    public function durationMinutes(): int
    {
        return $this->durationMinutes;
    }

    public function isActive(): bool
    {
        return $this->isActive;
    }

    public function inactiveService(): void
    {
        if (!$this->isActive) {
            throw new \DomainException('The service is already inactive');
        }

        $this->isActive = false;
    }

    public function activeService(): void
    {
        if ($this->isActive) {
            throw new \DomainException('The service is already active');
        }

        $this->isActive = true;
    }

    public function staffMember()
    {
        return $this->staffMember;
    }
}
