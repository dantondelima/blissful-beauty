<?php

declare(strict_types=1);

namespace App\Domain\Model\Order;

use App\Domain\Model\Customer;
use App\Domain\Model\Service\Schedule;


class Order
{
    public function __construct(
        private ?int $id,
        private Customer $customer,
        private Schedule $schedule
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

    public function customer(): Customer
    {
        return $this->customer;
    }

    public function schedule(): Schedule
    {
        return $this->schedule;
    }
}
