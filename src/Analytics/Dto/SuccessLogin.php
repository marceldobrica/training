<?php

declare(strict_types=1);

namespace App\Analytics\Dto;

class SuccessLogin
{
    private string $email;

    private int $loginCounts = 0;

    public function __construct(string $email)
    {
        $this->email = $email;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getLoginCounts(): int
    {
        return $this->loginCounts;
    }

    public function setLoginCounts(int $loginCounts): self
    {
        $this->loginCounts = $loginCounts;

        return $this;
    }
}
