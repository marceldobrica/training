<?php

declare(strict_types=1);

namespace App\Analytics\Dto;

class UsersCreatedByRole
{
    private string $role;

    private int $count = 0;

    private float $percent = 0;

    public function __construct(string $role)
    {
        $this->role = $role;
    }

    public function getRole(): string
    {
        return $this->role;
    }

    public function getCount(): int
    {
        return $this->count;
    }

    public function setCount(int $count): self
    {
        $this->count = $count;

        return $this;
    }

    public function getPercent()
    {
        return $this->percent;
    }

    public function setPercent($percent): void
    {
        $this->percent = $percent;
    }
}
