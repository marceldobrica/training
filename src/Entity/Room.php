<?php

namespace App\Entity;

class Room
{
    private int $id;

    public string $name;

    public int $capacity;

    public Building $build;

    public function getId(): int
    {
        return $this->id;
    }
}
