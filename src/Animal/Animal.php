<?php
declare(strict_types=1);

namespace App\Animal;

class Animal
{
    private int $belly = 50;

    public function getBelly(): int
    {
        return $this->belly;
    }

    public function setBelly(int $belly): self
    {
        $this->belly = $belly;

        return $this;
    }
}