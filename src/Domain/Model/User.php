<?php

declare(strict_types=1);

namespace App\Domain\Model;

class User
{
    public function __construct(private ?int $id, private string $name)
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
}
