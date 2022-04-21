<?php

declare(strict_types=1);

namespace App\Analytics;

use App\Analytics\Dto\AdminSuccessLogin;

class AdminSuccessLoginBucket
{
    private array $newAdminLoginsPerDay = [];

    public function add(AdminSuccessLogin $adminSuccessLogin): void
    {
        if (!isset($this->newAdminLoginsPerDay[$adminSuccessLogin->getDataKey()])) {
            $this->newAdminLoginsPerDay[$adminSuccessLogin->getDataKey()] = 0;
        }
        $this->newAdminLoginsPerDay[$adminSuccessLogin->getDataKey()] ++;
    }

    public function get(): \Generator
    {
        foreach ($this->newAdminLoginsPerDay as $key => $item) {
            $adminSuccessLogin = new AdminSuccessLogin($key);
            $adminSuccessLogin->setLoginCounts($item);

            yield $adminSuccessLogin;
        }
    }
}
