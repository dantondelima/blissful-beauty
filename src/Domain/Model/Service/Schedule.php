<?php

declare(strict_types=1);

namespace App\Domain\Model\Service;

use DateTimeInterface;

class Schedule
{
    public function __construct(
        private ?int $id,
        private DateTimeInterface $date,
        private Service $service,
        private ?bool $isTaken = false,
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

    public function date(): \DateTimeInterface
    {
        return $this->date;
    }

    public function isTaken(): bool
    {
        return $this->isTaken;
    }

    public function takeSchedule(): void
    {
        if ($this->isTaken()) {
            throw new \DomainException('The schedule is already taken');
        }

        $this->isTaken = true;
    }

    public function service()
    {
        return $this->service;
    }
}
