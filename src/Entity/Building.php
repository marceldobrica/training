<?php

namespace App\Entity;

class Building
{
    private int $id;

    public \DateTime $startTime;

    public \DateTime $endTime;

    public function getId(): int
    {
        return $this->id;
    }
}
