<?php

declare(strict_types=1);

namespace App\Analytics\Dto;

class AdminSuccessLogin
{
    private string $dataKey;

    private int $loginCounts = 0;

    public function __construct(string $dataKey)
    {
        $this->dataKey = $dataKey;
    }

    public function getDataKey(): string
    {
        return $this->dataKey;
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
